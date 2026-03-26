<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $month = now()->month;
        $year  = now()->year;

        // ── Revenue stats ──
        $totalSalesToday = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total_price');

        $yesterdaySales = Order::whereDate('created_at', now()->subDay()->toDateString())
            ->where('status', 'completed')
            ->sum('total_price');

        $revenueGrowth = $yesterdaySales > 0
            ? round((($totalSalesToday - $yesterdaySales) / $yesterdaySales) * 100, 1)
            : 0;
        $revenueGrowth = ($revenueGrowth >= 0 ? '+' : '') . $revenueGrowth;

        // ── Order counts ──
        $ordersToday     = Order::whereDate('created_at', $today)->count();
        $completedOrders = Order::whereDate('created_at', $today)->where('status', 'completed')->count();
        $completedRate   = $ordersToday > 0 ? round($completedOrders / $ordersToday * 100) : 0;

        $yesterdayOrders = Order::whereDate('created_at', now()->subDay()->toDateString())->count();
        $ordersGrowth    = $yesterdayOrders > 0
            ? ($ordersToday >= $yesterdayOrders ? '+' : '') . round((($ordersToday - $yesterdayOrders) / $yesterdayOrders) * 100, 1)
            : '+0';

        // ── Tables ──
        $activeTables = Table::where('status', 'occupied')->count();
        $totalTables  = Table::count();

        // ── Monthly goal ──
        $monthlySales = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'completed')
            ->sum('total_price');

        $monthlyGoal = 50_000_000; // Set your target here or from DB/config

        // ── Order status breakdown (today) ──
        $orderStatus = [
            'pending'    => Order::whereDate('created_at', $today)->where('status', 'pending')->count(),
            'paid' => Order::whereDate('created_at', $today)->where('status', 'paid')->count(),
            'completed'  => $completedOrders,
            'cancelled'  => Order::whereDate('created_at', $today)->where('status', 'cancelled')->count(),
        ];

        // ── Chart: last 7 days revenue ──
        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date          = now()->subDays($i);
            $chartLabels[] = $date->format('D d/m');
            $chartData[]   = (float) Order::whereDate('created_at', $date->toDateString())
                ->where('status', 'completed')
                ->sum('total_price');
        }

        // ── Recent orders (latest 8) ──
        $recentOrders = Order::with('table')
            ->latest()
            ->limit(8)
            ->get();

        // ── Top selling menus (this month) ──
        // Ada 2 sumber penjualan:
        //   Source A: order_items.menu_id IS NOT NULL  → customer order langsung ke menu
        //   Source B: order_items.price_offer_id IS NOT NULL → customer order via price offer,
        //             menu aslinya ada di price_offer_items.menu_id
        // Kedua sumber digabung (UNION ALL) lalu di-SUM per menu_id

        $sourceA = DB::table('order_items as oi')
            ->select('oi.menu_id', DB::raw('SUM(oi.qty) as qty'))
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->whereNotNull('oi.menu_id')
            ->whereMonth('o.created_at', $month)
            ->whereYear('o.created_at', $year)
            ->groupBy('oi.menu_id');

        $sourceB = DB::table('order_items as oi2')
            ->select('poi.menu_id', DB::raw('SUM(oi2.qty) as qty'))
            ->join('price_offer_items as poi', 'poi.price_offer_id', '=', 'oi2.price_offer_id')
            ->join('orders as o2', 'o2.id', '=', 'oi2.order_id')
            ->whereNotNull('oi2.price_offer_id')
            ->whereMonth('o2.created_at', $month)
            ->whereYear('o2.created_at', $year)
            ->groupBy('poi.menu_id');

        // Gabungkan A + B, lalu sum qty yang sama per menu_id
        $soldMap = DB::table(DB::raw('(' . $sourceA->toSql() . ' UNION ALL ' . $sourceB->toSql() . ') as combined'))
            ->mergeBindings($sourceA)
            ->mergeBindings($sourceB)
            ->select('menu_id', DB::raw('SUM(qty) as total_sold'))
            ->groupBy('menu_id')
            ->pluck('total_sold', 'menu_id');

        $topMenus = Menu::with('category')
            ->get()
            ->map(function ($menu) use ($soldMap) {
                $menu->total_sold = $soldMap->get($menu->id, 0);
                return $menu;
            })
            ->filter(fn($m) => $m->total_sold > 0)   // hanya menu yang terjual
            ->sortByDesc('total_sold')
            ->take(6)
            ->values();

        return view('dashboard.index', compact(
            'totalSalesToday',
            'revenueGrowth',
            'ordersToday',
            'ordersGrowth',
            'completedOrders',
            'completedRate',
            'activeTables',
            'totalTables',
            'monthlySales',
            'monthlyGoal',
            'orderStatus',
            'chartLabels',
            'chartData',
            'recentOrders',
            'topMenus',
        ));
    }

    public function chartData(Request $request)
    {
        $days = (int) $request->get('days', 7);
        $days = in_array($days, [7, 14, 30]) ? $days : 7;

        $labels = [];
        $data   = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date    = now()->subDays($i);
            $labels[] = $date->format('D d/m');
            $data[]   = (float) Order::whereDate('created_at', $date->toDateString())
                ->where('status', 'completed')
                ->sum('total_price');
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}

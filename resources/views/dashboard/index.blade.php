@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --info: #06b6d4;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-sub: #64748b;
            --text-muted: #94a3b8;
            --radius: 16px;
            --radius-sm: 10px;
            --shadow: 0 4px 24px rgba(99, 102, 241, 0.08);
            --shadow-md: 0 8px 32px rgba(99, 102, 241, 0.14);
        }

        .content-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface-2);
            padding: 28px 32px;
            min-height: 100vh;
        }

        /* ── Header ── */
        .dash-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dash-header-left h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
            margin: 0 0 4px;
        }

        .dash-header-left p {
            color: var(--text-sub);
            margin: 0;
            font-size: 0.875rem;
        }

        .date-badge {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-sub);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-badge i {
            color: var(--primary);
        }

        /* ── Stat Cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, #6366f1, #a78bfa);
        }

        .stat-card.amber::before {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .stat-card.cyan::before {
            background: linear-gradient(90deg, #06b6d4, #38bdf8);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 16px;
        }

        .stat-card.purple .stat-icon {
            background: #ede9fe;
            color: #6366f1;
        }

        .stat-card.amber .stat-icon {
            background: #fef3c7;
            color: #d97706;
        }

        .stat-card.green .stat-icon {
            background: #d1fae5;
            color: #059669;
        }

        .stat-card.cyan .stat-icon {
            background: #cffafe;
            color: #0891b2;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
            font-family: 'JetBrains Mono', monospace;
            margin-bottom: 10px;
        }

        .stat-change {
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.up {
            color: var(--success);
        }

        .stat-change.down {
            color: var(--danger);
        }

        /* ── Section Titles ── */
        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title span {
            flex: 1;
        }

        .section-title a {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
        }

        /* ── Main Grid ── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 1200px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Chart Card ── */
        .chart-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .chart-filter {
            display: flex;
            gap: 6px;
        }

        .chart-filter button {
            border: 1px solid var(--border);
            background: transparent;
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-sub);
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .chart-filter button.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        /* ── Order Status Card ── */
        .status-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .status-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .status-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-dot-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot.pending {
            background: #f59e0b;
        }

        .dot.process {
            background: #6366f1;
        }

        .dot.done {
            background: #10b981;
        }

        .dot.cancel {
            background: #ef4444;
        }

        .status-count {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-main);
            background: var(--surface-2);
            padding: 2px 10px;
            border-radius: 20px;
        }

        /* ── Bottom Grid ── */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 992px) {
            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Recent Orders Table ── */
        .table-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .dash-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dash-table th {
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 0 12px 12px;
            border-bottom: 1px solid var(--border);
        }

        .dash-table td {
            padding: 12px;
            font-size: 0.8125rem;
            color: var(--text-main);
            border-bottom: 1px solid #f1f5f9;
        }

        .dash-table tr:last-child td {
            border-bottom: none;
        }

        .dash-table tr:hover td {
            background: #fafbff;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .badge-status.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-status.process {
            background: #ede9fe;
            color: #6366f1;
        }

        .badge-status.done {
            background: #d1fae5;
            color: #059669;
        }

        .badge-status.cancel {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ── Top Menu ── */
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-rank {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--surface-2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--text-sub);
            flex-shrink: 0;
        }

        .menu-rank.top {
            background: #fef3c7;
            color: #d97706;
        }

        .menu-info {
            flex: 1;
            min-width: 0;
        }

        .menu-name {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-cat {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .menu-sold {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary);
            white-space: nowrap;
        }

        /* ── Quick Actions ── */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .quick-btn {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-main);
            font-size: 0.8125rem;
            font-weight: 600;
            transition: all 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
        }

        .quick-btn:hover {
            border-color: var(--primary);
            background: #fafaff;
            color: var(--primary);
            text-decoration: none;
        }

        .quick-btn i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .quick-btn.q1 i {
            background: #ede9fe;
            color: #6366f1;
        }

        .quick-btn.q2 i {
            background: #d1fae5;
            color: #059669;
        }

        .quick-btn.q3 i {
            background: #fef3c7;
            color: #d97706;
        }

        .quick-btn.q4 i {
            background: #cffafe;
            color: #0891b2;
        }

        /* ── Revenue Progress ── */
        .revenue-goal {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: var(--radius);
            padding: 24px;
            color: white;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .revenue-goal::after {
            content: '';
            position: absolute;
            right: -30px;
            top: -40px;
            width: 160px;
            height: 160px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .revenue-goal::before {
            content: '';
            position: absolute;
            right: 50px;
            bottom: -50px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .goal-label {
            font-size: 0.75rem;
            font-weight: 600;
            opacity: 0.75;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .goal-value {
            font-size: 1.75rem;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }

        .goal-bar-bg {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 99px;
            height: 6px;
            margin-bottom: 8px;
        }

        .goal-bar-fill {
            background: white;
            border-radius: 99px;
            height: 6px;
            transition: width 1s ease;
        }

        .goal-caption {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            opacity: 0.75;
        }
    </style>
@endpush

@section('content')

    {{-- ── HEADER ── --}}
    <div class="dash-header">
        <div class="dash-header-left">
            <h1>Dashboard</h1>
            <p>Welcome back, <strong>{{ Auth::user()->name }}</strong> — here's what's happening today.</p>
        </div>
        <div class="date-badge">
            <i class="mdi mdi-calendar-today"></i>
            <span id="live-date"></span>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stat-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="mdi mdi-currency-usd"></i></div>
            <div class="stat-label">Total Revenue Today</div>
            <div class="stat-value">Rp {{ number_format($totalSalesToday ?? 0) }}</div>
            <div class="stat-change up"><i class="mdi mdi-trending-up"></i> {{ $revenueGrowth ?? '+0' }}% vs yesterday</div>
        </div>

        <div class="stat-card amber">
            <div class="stat-icon"><i class="mdi mdi-cart-outline"></i></div>
            <div class="stat-label">Orders Today</div>
            <div class="stat-value">{{ $ordersToday ?? 0 }}</div>
            <div class="stat-change up"><i class="mdi mdi-trending-up"></i> {{ $ordersGrowth ?? '+0' }}% vs yesterday</div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon"><i class="mdi mdi-check-circle-outline"></i></div>
            <div class="stat-label">Completed Orders</div>
            <div class="stat-value">{{ $completedOrders ?? 0 }}</div>
            <div class="stat-change up"><i class="mdi mdi-trending-up"></i> {{ $completedRate ?? '0' }}% completion rate
            </div>
        </div>

        <div class="stat-card cyan">
            <div class="stat-icon"><i class="mdi mdi-table-chair"></i></div>
            <div class="stat-label">Active Tables</div>
            <div class="stat-value">{{ $activeTables ?? 0 }} / {{ $totalTables ?? 0 }}</div>
            <div class="stat-change {{ ($activeTables ?? 0) > 0 ? 'up' : '' }}">
                <i class="mdi mdi-circle-medium"></i> {{ $activeTables ?? 0 }} currently occupied
            </div>
        </div>
    </div>

    {{-- ── REVENUE GOAL ── --}}
    @php
        $goalAmount = $monthlyGoal ?? 50000000;
        $currentMonth = $monthlySales ?? 0;
        $pct = $goalAmount > 0 ? min(100, round(($currentMonth / $goalAmount) * 100)) : 0;
    @endphp
    <div class="revenue-goal">
        <div class="goal-label">Monthly Revenue Goal</div>
        <div class="goal-value">Rp {{ number_format($currentMonth) }}</div>
        <div class="goal-bar-bg">
            <div class="goal-bar-fill" style="width: {{ $pct }}%"></div>
        </div>
        <div class="goal-caption">
            <span>{{ $pct }}% of target</span>
            <span>Target: Rp {{ number_format($goalAmount) }}</span>
        </div>
    </div>

    {{-- ── QUICK ACTIONS ── --}}
    <div class="quick-actions">
        {{-- <a href="{{ route('orders.create') }}" class="quick-btn q1">
            <i class="mdi mdi-plus-circle-outline"></i> New Order
        </a> --}}
        <a href="{{ route('price-offers.index') }}" class="quick-btn q3">
            <i class="mdi mdi-tag-outline"></i> Price Offers
        </a>
        <a href="{{ route('menus.index') }}" class="quick-btn q2">
            <i class="mdi mdi-food-fork-drink"></i> Manage Menu
        </a>
        <a href="{{ route('tables.index') }}" class="quick-btn q4">
            <i class="mdi mdi-table-edit"></i> Manage Tables
        </a>
    </div>

    {{-- ── MAIN GRID: Chart + Status ── --}}
    <div class="main-grid">

        {{-- Sales Chart --}}
        <div class="chart-card">
            <div class="section-title">
                <span><i class="mdi mdi-chart-line" style="color:var(--primary)"></i> Revenue Trend</span>
                <div class="chart-filter">
                    <button class="active" onclick="setFilter(this,'7')">7D</button>
                    <button onclick="setFilter(this,'14')">14D</button>
                    <button onclick="setFilter(this,'30')">30D</button>
                </div>
            </div>
            <canvas id="salesChart" height="260"></canvas>
        </div>

        {{-- Order Status --}}
        <div class="status-card">
            <div class="section-title">
                <span><i class="mdi mdi-clipboard-list-outline" style="color:var(--primary)"></i> Order Status</span>
            </div>

            <canvas id="donutChart" height="170"></canvas>

            <ul class="status-list" style="margin-top:16px">
                <li class="status-item">
                    <span class="status-dot-label"><span class="dot pending"></span> Pending</span>
                    <span class="status-count">{{ $orderStatus['pending'] ?? 0 }}</span>
                </li>
                <li class="status-item">
                    <span class="status-dot-label"><span class="dot process"></span> Paid</span>
                    <span class="status-count">{{ $orderStatus['paid'] ?? 0 }}</span>
                </li>
                <li class="status-item">
                    <span class="status-dot-label"><span class="dot done"></span> Completed</span>
                    <span class="status-count">{{ $orderStatus['completed'] ?? 0 }}</span>
                </li>
                <li class="status-item">
                    <span class="status-dot-label"><span class="dot cancel"></span> Cancelled</span>
                    <span class="status-count">{{ $orderStatus['cancelled'] ?? 0 }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- ── BOTTOM GRID: Recent Orders + Top Menu ── --}}
    <div class="bottom-grid">

        {{-- Recent Orders --}}
        <div class="table-card">
            <div class="section-title">
                <span><i class="mdi mdi-receipt" style="color:var(--primary)"></i> Recent Orders</span>
                <a href="{{ route('orders.index') }}">View all →</a>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Table</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->table->kode_table ?? '-' }}</td>
                            <td style="font-family:'JetBrains Mono',monospace;font-weight:700">
                                Rp {{ number_format($order->total_price) }}
                            </td>
                            <td>
                                <span class="badge-status {{ $order->status }}">
                                    <span class="dot {{ $order->status }}" style="width:6px;height:6px"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted);font-size:0.75rem">
                                {{ $order->created_at->format('H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:24px;color:var(--text-muted)">
                                <i class="mdi mdi-inbox-outline"
                                    style="font-size:2rem;display:block;margin-bottom:8px"></i>
                                No orders yet today
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Top Menu --}}
        <div class="table-card">
            <div class="section-title">
                <span><i class="mdi mdi-fire" style="color:var(--accent)"></i> Top Selling Menu</span>
                <a href="{{ route('menus.index') }}">View all →</a>
            </div>
            <ul class="menu-list">
                @forelse($topMenus ?? [] as $i => $menu)
                    <li class="menu-item">
                        <div class="menu-rank {{ $i === 0 ? 'top' : '' }}">{{ $i + 1 }}</div>
                        <div class="menu-info">
                            <div class="menu-name">{{ $menu->name }}</div>
                            <div class="menu-cat">{{ $menu->category->name ?? 'Uncategorized' }}</div>
                        </div>
                        <div class="menu-sold">{{ $menu->total_sold ?? 0 }} sold</div>
                    </li>
                @empty
                    <li style="padding:24px;text-align:center;color:var(--text-muted)">
                        <i class="mdi mdi-food-off" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                        No sales data yet
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Live date ──
        (function() {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const now = new Date();
            document.getElementById('live-date').textContent =
                `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        })();

        // ── Sales chart data (pass from controller) ──
        @php
            $safeLabels = $chartLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $safeData = $chartData ?? [120000, 350000, 280000, 490000, 310000, 620000, 540000];
        @endphp
        const salesLabels = {!! json_encode($safeLabels) !!};
        const salesData = {!! json_encode($safeData) !!};

        const ctxLine = document.getElementById('salesChart').getContext('2d');

        const gradient = ctxLine.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(99,102,241,0.18)');
        gradient.addColorStop(1, 'rgba(99,102,241,0)');

        const salesChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Revenue',
                    data: salesData,
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#94a3b8',
                        bodyColor: '#f8fafc',
                        padding: 12,
                        callbacks: {
                            label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            },
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: 'JetBrains Mono',
                                size: 10
                            },
                            color: '#94a3b8',
                            callback: v => 'Rp ' + (v / 1000).toFixed(0) + 'K'
                        }
                    }
                }
            }
        });

        // ── Chart period filter ──
        function setFilter(btn, days) {
            document.querySelectorAll('.chart-filter button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            fetch(`/dashboard/chart-data?days=${days}`)
                .then(r => r.json())
                .then(({
                    labels,
                    data
                }) => {
                    salesChart.data.labels = labels;
                    salesChart.data.datasets[0].data = data;
                    salesChart.update();
                })
                .catch(console.error);
        }

        // ── Donut chart ──
        const orderStatus = {
            pending: {{ $orderStatus['pending'] ?? 4 }},
            paid: {{ $orderStatus['paid'] ?? 7 }},
            completed: {{ $orderStatus['completed'] ?? 28 }},
            cancelled: {{ $orderStatus['cancelled'] ?? 2 }}
        };

        new Chart(document.getElementById('donutChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Paid', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        orderStatus.pending,
                        orderStatus.paid,
                        orderStatus.completed,
                        orderStatus.cancelled
                    ],
                    backgroundColor: ['#fbbf24', '#818cf8', '#34d399', '#f87171'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        bodyColor: '#f8fafc',
                        padding: 10
                    }
                }
            }
        });
    </script>
@endpush

<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Events\OrderStatusUpdated;
use App\Events\PaymentUploaded;
use App\Models\Category;
use App\Models\Menu;
// use App\Models\OptionGroup;
use App\Models\Order;
// use App\Models\OrderItem;
use App\Models\Pajak;
use App\Models\PriceOffer;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $data = Order::orderBy('created_at', 'desc')->cursorPaginate(8);
        $order = $request->status;

        $data = Order::with('table')
            ->when($order, function ($q) use ($order) {
                $q->where('status', $order);
            })
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(8)
            ->withQueryString(); // PENTING

        if ($request->ajax()) {
            return response(
                view('admin.order._items', compact('data'))->render()
            )->header(
                'X-Cursor',
                optional($data->nextCursor())->encode()
            );
        }

        return view('admin.order.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tables = Table::where('status', 'available')->get();
        return view('coba', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $validated = $request->validate([
    //         'customer_name' => 'required|string|max:255',
    //         'customer_email' => 'nullable|email|max:255',
    //         'customer_phone' => 'nullable|string|max:20',
    //         'table_id' => 'required|exists:tables,id',
    //         'subtotal' => 'required|numeric|min:0',
    //         'tax_amount' => 'nullable|numeric|min:0',
    //         'discount_amount' => 'nullable|numeric|min:0',
    //         'service_fee' => 'nullable|numeric|min:0',
    //         'total_price' => 'required|numeric|min:0',
    //         'status' => 'nullable|in:pending,paid,completed,cancelled'
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         // Set default values jika tidak ada
    //         $validated['tax_amount'] = $validated['tax_amount'] ?? 0;
    //         $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
    //         $validated['service_fee'] = $validated['service_fee'] ?? 0;
    //         $validated['status'] = $validated['status'] ?? 'pending';

    //         // Hitung ulang total price untuk memastikan
    //         $validated['total_price'] =
    //             $validated['subtotal']
    //             + $validated['tax_amount']
    //             + $validated['service_fee']
    //             - $validated['discount_amount'];

    //         // Create order (order_year, order_number, order_code akan di-generate otomatis)
    //         $order = Order::create($validated);

    //         // Update table status menjadi occupied
    //         Table::where('id', $validated['table_id'])->update(['status' => 'occupied']);

    //         DB::commit();

    //         // ✅ LOG SEBELUM BROADCAST
    //         Log::info('🔔 BROADCASTING ORDER', [
    //             'order_code' => $order->order_code,
    //             'order_id' => $order->id,
    //             'table' => $order->table->name ?? 'N/A'
    //         ]);

    //         // 🔔 BROADCAST EVENT - Kirim notifikasi real-time ke kasir
    //         // broadcast(new OrderCreated($order->load('table')))->toOthers();
    //         broadcast(new OrderCreated($order->load('table')));

    //         Log::info('✅ BROADCAST BERHASIL');


    //         return redirect()
    //             ->route('orders.show', $order->id)
    //             ->with('success', 'Order berhasil dibuat dengan kode: ' . $order->order_code);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('❌ ERROR BROADCAST', [
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);


    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->with('error', 'Gagal membuat order: ' . $e->getMessage());
    //     }
    // }

    // public function store(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Generate order number
    //         $year = now()->year;
    //         $lastOrder = Order::where('order_year', $year)->max('order_number') ?? 0;
    //         $orderNumber = $lastOrder + 1;
    //         $orderCode = sprintf('INV-%d-%05d', $year, $orderNumber);

    //         // Create order
    //         $order = Order::create([
    //             'order_year' => $year,
    //             'order_number' => $orderNumber,
    //             'order_code' => $orderCode,
    //             'table_id' => $request->table_id,
    //             'customer_name' => $request->customer_name,
    //             'subtotal' => $request->subtotal,
    //             'tax_amount' => $request->tax_amount,
    //             'total_price' => $request->total_price,
    //             'status' => 'pending'
    //         ]);

    //         // Create order items
    //         foreach ($request->items as $itemData) {
    //             $menu = Menu::find($itemData['menu_id']);

    //             $orderItem = $order->items()->create([
    //                 'menu_id' => $menu->id,
    //                 'menu_name' => $menu->name,
    //                 'price' => $itemData['price'],
    //                 'qty' => $itemData['qty'],
    //                 'subtotal' => $itemData['price'] * $itemData['qty'],
    //                 'notes' => $itemData['notes'] ?? null
    //             ]);

    //             // Save selected options
    //             if (isset($itemData['options']) && is_array($itemData['options'])) {
    //                 foreach ($itemData['options'] as $groupId => $options) {
    //                     $optionGroup = OptionGroup::find($groupId);

    //                     foreach ($options as $optionData) {
    //                         $orderItem->selectedOptions()->create([
    //                             'option_group_id' => $groupId,
    //                             'option_group_name' => $optionGroup->name,
    //                             'option_group_type' => $optionGroup->type,
    //                             'option_id' => $optionData['option_id'] ?? null,
    //                             'option_name' => $optionData['option_name'] ?? null,
    //                             'option_price' => $optionData['extra_price'] ?? 0,
    //                             'custom_value' => $optionData['custom_value'] ?? null
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return redirect()->route('order.success', ['order' => $order->id]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
    //     }
    // }

    // public function store1(Request $request)
    // {
    //     $validated = $request->validate([
    //         'table_id' => 'required|exists:tables,id',
    //         'customer_name' => 'required|string',
    //         'items' => 'required|array|min:1',
    //         'items.*.menu_id' => 'required|exists:menus,id',
    //         'items.*.qty' => 'required|integer|min:1',
    //         'items.*.notes' => 'nullable|string',
    //         'items.*.options' => 'nullable|json',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         // Hitung ulang harga di server
    //         $calculatedSubtotal = 0;
    //         $orderItems = [];

    //         foreach ($validated['items'] as $item) {
    //             // Ambil menu dari database
    //             $menu = Menu::findOrFail($item['menu_id']);

    //             // Mulai dari harga dasar menu
    //             $itemPrice = $menu->price;

    //             // Hitung extra price dari options
    //             if (!empty($item['options'])) {
    //                 $options = json_decode($item['options'], true);

    //                 foreach ($options as $groupId => $selectedOptions) {
    //                     foreach ($selectedOptions as $option) {
    //                         if (isset($option['option_id'])) {
    //                             // Validasi option benar-benar ada di database
    //                             $menuOption = MenuOption::where('id', $option['option_id'])
    //                                 ->whereHas('optionGroups', function ($q) use ($item) {
    //                                     $q->whereHas('menus', function ($q2) use ($item) {
    //                                         $q2->where('menus.id', $item['menu_id']);
    //                                     });
    //                                 })
    //                                 ->first();

    //                             if ($menuOption) {
    //                                 $itemPrice += $menuOption->extra_price;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             $itemTotal = $itemPrice * $item['qty'];
    //             $calculatedSubtotal += $itemTotal;

    //             $orderItems[] = [
    //                 'menu_id' => $item['menu_id'],
    //                 'qty' => $item['qty'],
    //                 'price' => $itemPrice, // Harga yang sudah divalidasi
    //                 'notes' => $item['notes'] ?? null,
    //                 'options' => $item['options'] ?? null,
    //             ];
    //         }

    //         // Hitung tax dan total
    //         $taxAmount = $calculatedSubtotal * 0.1;
    //         $totalPrice = $calculatedSubtotal + $taxAmount;

    //         // Buat order
    //         $order = Order::create([
    //             'table_id' => $validated['table_id'],
    //             'customer_name' => $validated['customer_name'],
    //             'subtotal' => $calculatedSubtotal,
    //             'tax_amount' => $taxAmount,
    //             'total_price' => $totalPrice,
    //             'status' => 'pending',
    //         ]);

    //         // Simpan order items
    //         foreach ($orderItems as $item) {
    //             $order->items()->create($item);
    //         }

    //         DB::commit();

    //         return redirect()->route('order.success', ['order' => $order->id])
    //             ->with('success', 'Pesanan berhasil dibuat!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
    //     }
    // }


    /**
     * Display the specified resource.
     */
    // public function show(Order $order)
    // {
    //     $order->load([
    //         'table:id,kode_table',
    //         'items.menu'
    //     ]);

    //     return response()->json([
    //         'order_code'    => $order->order_code,
    //         'tableKode'     => optional($order->table)->kode_table,
    //         'customer_name' => $order->customer_name,
    //         'status'        => $order->status,
    //         'total_price'   => $order->total_price,
    //         'items'         => $order->items,
    //     ]);
    // }

    public function show(Order $order)
    {
        if (request()->ajax()) {

            $order->load([
                'table',
                'items.menu',             // null untuk item paket
                'items.options',          // opsi menu biasa
                'items.priceOffer.items', // konten isi paket bundling
            ]);

            return response()->json([
                'order_code'      => $order->order_code,
                'customer_name'   => $order->customer_name,
                'customer_email'  => $order->customer_email,
                'customer_phone'  => $order->customer_phone,
                'tableKode'       => optional($order->table)->kode_table,
                'tableFloor'      => optional($order->table)->floor,
                'status'          => $order->status,
                'created_at'      => $order->created_at,

                // Pembayaran
                'buktiPembayaran' => $order->buktiPembayaran,
                'notePembayaran'  => $order->notePembayaran,

                // Harga
                'subtotal'    => $order->subtotal,
                'tax_amount'  => $order->tax_amount,
                'service_fee' => $order->service_fee,
                'total_price' => $order->total_price,

                // Items — dibedakan via item_type agar JS bisa render per section
                'items' => $order->items->map(function ($item) {

                    // Field yang sama untuk semua tipe
                    $base = [
                        'id'        => $item->id,
                        'item_type' => $item->item_type,  // 'menu' | 'package'
                        'item_name' => $item->item_name,  // nama yang tersimpan saat order, selalu ada
                        'qty'       => $item->qty,
                        'price'     => $item->price,
                        'subtotal'  => $item->subtotal,
                    ];

                    if ($item->item_type === 'package') {
                        // PAKET — tidak ada relasi menu, ambil isi dari priceOffer.items
                        $base['package_contents'] = $item->priceOffer
                            ? $item->priceOffer->items->map(fn($c) => [
                                'name'     => $c->item_name,
                                'quantity' => $c->quantity,
                            ])->values()
                            : [];
                    } else {
                        // MENU BIASA — gunakan nullsafe ?-> agar tidak error jika menu terhapus
                        $base['menu_name'] = $item->menu?->name ?? $item->item_name;
                        $base['options']   = $item->options->map(fn($opt) => [
                            'option_group_name' => $opt->option_group_name,
                            'option_group_type' => $opt->option_group_type,
                            'option_name'       => $opt->option_name,
                            'option_price'      => $opt->option_price,
                            'custom_value'      => $opt->custom_value,
                        ]);
                    }

                    return $base;
                }),
            ]);
        }

        return view('admin.order.show', compact('order'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        // Broadcast event untuk real-time
        broadcast(new OrderStatusUpdated($order))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        }

        return redirect()->back()->with('success', 'Status berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    // public function orderByTable($table_id)
    // {
    //     $orders = Order::where('table_id', $table_id)->orderBy('created_at', 'desc')->get();
    //     return view('orders.by_table', compact('orders', 'table_id'));
    //     // return response()->json([
    //     //     'order' => $orders
    //     // ], 200);
    // }


            // if ($table->status !== 'available') {
            //     return redirect()->back()->with('error', 'Table tidak tersedia');
            // }

            // Load menu dengan pagination
            // $data = Menu::with(['category', 'optionGroups'])
            //     ->where('is_available', true)
            //     ->when($request->search, function ($query, $search) {
            //         $query->where('name', 'like', "%{$search}%");
            //     })
            //     ->when($request->category_id, function ($query, $categoryId) {
            //         $query->where('category_id', $categoryId);
            //     })
            //     ->cursorPaginate(6);

            // $data = Menu::with('category')
            //     // ->join('categories', 'menus.category_id', '=', 'categories.id')
            //     // ->select('menus.*') // penting! agar tidak konflik kolom
            //     // ->when($categoryId, fn($q) => $q->where('menus.category_id', $categoryId))
            //     ->when($request->category_id, function ($query, $categoryId) {
            //         $query->where('category_id', $categoryId);
            //     })
            //     ->when($request->search, function ($query, $search) {
            //         $query->where('name', 'like', "%{$search}%");
            //     })
            //     // ->when($search, function ($q) use ($search) {
            //     //     $q->where('menus.name', 'like', "%{$search}%")
            //     //         ->orWhere('categories.name', 'like', "%{$search}%");
            //     // })
            //     // ->where('menus.is_available', true)
            //     // ->orderBy('categories.name', 'asc')  // urutkan berdasarkan nama kategori
            //     // ->orderBy('menus.id', 'desc')        // kemudian urutkan berdasarkan menu ID
            //     ->orderBy('id', 'desc')        // kemudian urutkan berdasarkan menu ID
            //     ->cursorPaginate(6);
            // // ->withQueryString();

            // // Load categories untuk filter
            // // $categories = \App\Models\Category::withCount('menus')->get();
            // $categories = Category::whereHas('menus', function ($query) {
            //     $query->where('is_available', true);
            // })
            //     ->orderBy('name')
            //     ->get();
    /**
     * ✅ TAMPILKAN HALAMAN MENU
     */
    public function menu(Request $request)
    {
        try {
            // Decrypt table ID dari query parameter
            $encryptedTableId = $request->query('table');

            if (!$encryptedTableId) {
                return redirect()->back()->with('error', 'Table tidak valid');
            }

            $tableId = Crypt::decryptString($encryptedTableId);

            // Validasi table exists dan available
            $table = Table::findOrFail($tableId);

            $search = $request->search;
            $isAvailable = $request->is_available; // new filter
            $categoryId = $request->category_id;

            $categories = Category:: //whereHas('menus', function ($query) {
                // $query->where('is_available', true);
                // })
                whereNull('parent_id')
                ->with('childrenRecursive')
                ->orderBy('name')
                ->get();

            $categoryIds = [];

            if ($categoryId) {
                $selectedCategory = Category::with('childrenRecursive')->find($categoryId);

                if ($selectedCategory) {
                    $categoryIds = $selectedCategory->getAllCategoryIds($selectedCategory);
                }
            }

            $data = Menu::with('category.parent')
                ->when(!empty($categoryIds), function ($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds);
                })
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhereHas('category', function ($cat) use ($search) {
                                $cat->where('name', 'like', "%{$search}%")
                                    ->orWhereHas('parent', function ($parent) use ($search) {
                                        $parent->where('name', 'like', "%{$search}%");
                                    });
                            });
                    });
                })
                // ->when($request->filled('is_available'), function ($q) use ($isAvailable) {
                //     $q->where('is_available', (bool) $isAvailable);
                // })
                ->orderBy('id', 'desc')
                ->cursorPaginate(6)
                ->withQueryString();

            // Jika AJAX request, return partial view
            if ($request->ajax()) {
                $html = view('order._items', compact('data'))->render();

                return response($html)
                    ->header('X-Cursor', $data->nextCursor()?->encode());
            }

            // $packages = PriceOffer::where('is_active', true)->get();
            $now = Carbon::now()->format('H:i:s');

            $packages = PriceOffer::where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('available_from')
                        ->orWhere('available_from', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('available_until')
                        ->orWhere('available_until', '>=', $now);
                })
                ->get();

            return view('order.menu', compact('table', 'data', 'categories', 'packages'));
        } catch (\Exception $e) {
            Log::error('Menu page error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * ✅ STORE ORDER - DENGAN VALIDASI KETAT
     */
    // public function store(Request $request)
    // {
    //     // VALIDASI INPUT
    //     $validated = $request->validate([
    //         'table_id' => 'required|exists:tables,id',
    //         'customer_name' => 'required|string|min:3|max:100',
    //         'customer_email' => 'nullable|email|max:100',
    //         'customer_phone' => 'nullable|regex:/^[0-9]{10,15}$/|max:15',
    //         'items' => 'required|array|min:1|max:50',
    //         'items.*.menu_id' => 'required|exists:menus,id',
    //         'items.*.qty' => 'required|integer|min:1|max:100',
    //         // 'items.*.notes' => 'nullable|string|max:500',
    //         'items.*.options' => 'nullable|json|max:5000',
    //     ], [
    //         'customer_name.required' => 'Nama pelanggan wajib diisi',
    //         'customer_name.min' => 'Nama minimal 3 karakter',
    //         'customer_email.email' => 'Format email tidak valid',
    //         'customer_phone.regex' => 'Format nomor telepon tidak valid (10-15 digit)',
    //         'items.required' => 'Minimal pilih 1 menu',
    //         'items.*.qty.min' => 'Jumlah minimal 1',
    //         'items.*.qty.max' => 'Jumlah maksimal 100 per item',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // ✅ VALIDASI TABLE MASIH AVAILABLE
    //         $table = Table::lockForUpdate()->findOrFail($validated['table_id']);

    //         // if (!$table->is_available) {
    //         //     throw new \Exception("Table sudah tidak tersedia");
    //         // }

    //         // ✅ HITUNG ULANG SEMUA HARGA DI SERVER
    //         $calculatedSubtotal = 0;
    //         $orderItemsData = [];

    //         foreach ($validated['items'] as $itemInput) {
    //             // Load menu dengan relationships
    //             $menu = Menu::with('optionGroups.options')
    //                 ->findOrFail($itemInput['menu_id']);

    //             // Cek menu available
    //             if (!$menu->is_available) {
    //                 throw new \Exception("Menu {$menu->name} tidak tersedia");
    //             }

    //             // ✅ MULAI DARI HARGA DASAR MENU (DARI DATABASE!)
    //             $itemPrice = $menu->price;
    //             $itemOptionsData = [];

    //             // ✅ PROSES & VALIDASI OPTIONS
    //             if (!empty($itemInput['options'])) {
    //                 $optionsInput = json_decode($itemInput['options'], true);

    //                 if (!is_array($optionsInput)) {
    //                     throw new \Exception("Format options tidak valid");
    //                 }

    //                 foreach ($optionsInput as $groupId => $selectedOptions) {
    //                     // Validasi option group ada di menu
    //                     $optionGroup = $menu->optionGroups->firstWhere('id', $groupId);

    //                     if (!$optionGroup) {
    //                         throw new \Exception("Option group tidak valid");
    //                     }

    //                     // Validasi required options
    //                     if ($optionGroup->pivot->is_required && empty($selectedOptions)) {
    //                         throw new \Exception("Option '{$optionGroup->name}' wajib dipilih");
    //                     }

    //                     // Validasi single choice (hanya 1 pilihan)
    //                     if ($optionGroup->type === 'single' && count($selectedOptions) > 1) {
    //                         throw new \Exception("Hanya boleh pilih 1 untuk '{$optionGroup->name}'");
    //                     }

    //                     // Validasi multiple choice limit
    //                     if ($optionGroup->type === 'multiple' && count($selectedOptions) > 10) {
    //                         throw new \Exception("Maksimal 10 pilihan untuk '{$optionGroup->name}'");
    //                     }

    //                     // Proses setiap option
    //                     foreach ($selectedOptions as $optionData) {
    //                         // Handle custom text input
    //                         if (isset($optionData['custom_value'])) {
    //                             if ($optionGroup->type !== 'text') {
    //                                 throw new \Exception("Custom value tidak valid");
    //                             }

    //                             $itemOptionsData[] = [
    //                                 'option_group_id' => $optionGroup->id,
    //                                 'option_group_name' => $optionGroup->name,
    //                                 'option_group_type' => $optionGroup->type,
    //                                 'option_id' => null,
    //                                 'option_name' => null,
    //                                 'option_price' => 0,
    //                                 'custom_value' => strip_tags($optionData['custom_value']),
    //                             ];
    //                             continue;
    //                         }

    //                         // Validasi option_id exists
    //                         if (!isset($optionData['option_id'])) {
    //                             throw new \Exception("Option ID tidak valid");
    //                         }

    //                         // ✅ AMBIL OPTION DARI DATABASE (BUKAN DARI CLIENT!)
    //                         $menuOption = $optionGroup->options
    //                             ->firstWhere('id', $optionData['option_id']);

    //                         if (!$menuOption) {
    //                             throw new \Exception("Option tidak ditemukan");
    //                         }

    //                         // ✅ TAMBAHKAN EXTRA PRICE (DARI DATABASE!)
    //                         $itemPrice += $menuOption->extra_price;

    //                         // Simpan data option untuk database
    //                         $itemOptionsData[] = [
    //                             'option_group_id' => $optionGroup->id,
    //                             'option_group_name' => $optionGroup->name,
    //                             'option_group_type' => $optionGroup->type,
    //                             'option_id' => $menuOption->id,
    //                             'option_name' => $menuOption->name,
    //                             'option_price' => $menuOption->extra_price,
    //                             'custom_value' => null,
    //                         ];
    //                     }
    //                 }
    //             }

    //             // ✅ VALIDASI HARGA MASUK AKAL
    //             if ($itemPrice < 0 || $itemPrice > 10000000) {
    //                 throw new \Exception("Harga tidak valid untuk menu {$menu->name}");
    //             }

    //             // Hitung subtotal item
    //             $itemSubtotal = $itemPrice * $itemInput['qty'];
    //             $calculatedSubtotal += $itemSubtotal;

    //             // Simpan data untuk insert nanti
    //             $orderItemsData[] = [
    //                 'menu_id' => $menu->id,
    //                 'qty' => $itemInput['qty'],
    //                 'price' => $itemPrice,
    //                 'subtotal' => $itemSubtotal,
    //                 'options' => $itemOptionsData,
    //             ];
    //         }

    //         // ✅ VALIDASI TOTAL AKHIR
    //         if ($calculatedSubtotal < 1000) {
    //             throw new \Exception("Minimal order Rp 1.000");
    //         }

    //         if ($calculatedSubtotal > 100000000) {
    //             throw new \Exception("Maksimal order Rp 100.000.000");
    //         }

    //         // // ✅ HITUNG TAX, SERVICE FEE, DISCOUNT
    //         // $tax = Pajak::first();
    //         // $taxAmount = round($calculatedSubtotal * ($tax->ppn / 100), 2); // Tax from database
    //         // $serviceFee = round($taxAmount * ($tax->service_fee / 100), 2); // Service fee from database
    //         // // $serviceFee = round($calculatedSubtotal * ($tax->service_fee / 100), 2); // Service fee from database
    //         // $discountAmount = 0; // Bisa dikembangkan dengan promo code

    //         // $totalPrice = $calculatedSubtotal + $taxAmount + $serviceFee - $discountAmount;

    //         // ✅ LEBIH AMAN
    //         $pajak = Pajak::first();

    //         if (!$pajak) {
    //             // Fallback jika data pajak tidak ada
    //             throw new \Exception("Data pajak tidak ditemukan. Hubungi administrator.");
    //         }

    //         // Validasi field tidak null
    //         $ppnRate = $pajak->ppn ?? 10; // Default 10% jika null
    //         $serviceFeeRate = $pajak->service_fee ?? 50; // Default 50% jika null

    //         $taxAmount = round($calculatedSubtotal * ($ppnRate / 100), 2);
    //         $serviceFee = round($taxAmount * ($serviceFeeRate / 100), 2);
    //         $discountAmount = 0;

    //         $totalPrice = $calculatedSubtotal + $taxAmount + $serviceFee - $discountAmount;

    //         // ✅ CREATE ORDER
    //         $order = Order::create([
    //             'table_id' => $validated['table_id'],
    //             'customer_name' => strip_tags($validated['customer_name']),
    //             'customer_email' => $validated['customer_email'] ?? null,
    //             'customer_phone' => $validated['customer_phone'] ?? null,
    //             'status' => 'pending',
    //             'subtotal' => $calculatedSubtotal,
    //             'tax_amount' => $taxAmount,
    //             'service_fee' => $serviceFee,
    //             'discount_amount' => $discountAmount,
    //             'total_price' => $totalPrice,
    //         ]);

    //         // ✅ CREATE ORDER ITEMS & OPTIONS
    //         foreach ($orderItemsData as $itemData) {
    //             $orderItem = $order->items()->create([
    //                 'menu_id' => $itemData['menu_id'],
    //                 'qty' => $itemData['qty'],
    //                 'price' => $itemData['price'],
    //                 'subtotal' => $itemData['subtotal'],
    //             ]);

    //             // Insert options jika ada
    //             if (!empty($itemData['options'])) {
    //                 foreach ($itemData['options'] as $optionData) {
    //                     $orderItem->options()->create($optionData);
    //                 }
    //             }
    //         }

    //         // ✅ UPDATE TABLE STATUS
    //         $table->update(['status' => 'occupied']);

    //         // ✅ REFRESH ORDER UNTUK MENDAPATKAN order_code YANG SUDAH DI-GENERATE
    //         $order->refresh();

    //         DB::commit();

    //         // ✅ LOG UNTUK AUDIT
    //         Log::info('Order created successfully', [
    //             'order_id' => $order->id,
    //             'order_code' => $order->order_code,
    //             'total' => $totalPrice,
    //             'table_id' => $table->id,
    //             'ip' => $request->ip(),
    //             'user_agent' => $request->userAgent(),
    //         ]);

    //         // ✅ LOG SEBELUM BROADCAST
    //         Log::info('🔔 BROADCASTING ORDER', [
    //             'order_code' => $order->order_code,
    //             'order_id' => $order->id,
    //             'table' => $order->table->name ?? 'N/A'
    //         ]);

    //         // 🔔 BROADCAST EVENT - Kirim notifikasi real-time ke kasir
    //         // broadcast(new OrderCreated($order->load('table')))->toOthers();
    //         broadcast(new OrderCreated($order->load('table')));

    //         Log::info('✅ BROADCAST BERHASIL');

    //         // ✅ REDIRECT DENGAN order_code (BUKAN order->id)
    //         // return redirect()
    //         //     ->route('order.confirmation', ['orderCode' => $order->order_code])
    //         //     ->with('success', 'Pesanan berhasil dibuat!');

    //         // dd($order->orderCode);
    //         // $encryptedCode = urlencode(Crypt::encryptString($order->order_code));

    //         // return redirect()->route('order.confirmation', [
    //         //     'orderCode' => $encryptedCode
    //         // ]);
    //         // ✅ Sesudah — langsung encrypt tanpa urlencode
    //         $encryptedCode = Crypt::encryptString($order->order_code);

    //         return redirect()->route('order.confirmation', [
    //             'orderCode' => $encryptedCode
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         return back()
    //             ->withErrors($e->errors())
    //             ->withInput();
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         // Log error
    //         Log::error('Order creation failed', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //             'ip' => $request->ip(),
    //             'input' => $request->except(['_token']),
    //         ]);

    //         return back()
    //             ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }


    // ============================================================
    // OrderController@store — menangani menu biasa + paket bundling
    // ============================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id'                  => 'required|exists:tables,id',
            'customer_name'             => 'required|string|min:3|max:100',
            'customer_email'            => 'nullable|email|max:100',
            'customer_phone'            => 'nullable|regex:/^[0-9]{10,15}$/|max:15',

            // Menu biasa (boleh kosong jika semua paket)
            'items'                     => 'nullable|array|max:50',
            'items.*.menu_id'           => 'required_with:items|exists:menus,id',
            'items.*.qty'               => 'required_with:items|integer|min:1|max:100',
            'items.*.options'           => 'nullable|json|max:5000',

            // Paket bundling (boleh kosong jika semua menu biasa)
            'packages'                  => 'nullable|array|max:20',
            'packages.*.price_offer_id' => 'required_with:packages|exists:price_offers,id',
            'packages.*.qty'            => 'required_with:packages|integer|min:1|max:10',
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi',
            'customer_name.min'      => 'Nama minimal 3 karakter',
        ]);

        // Pastikan minimal ada 1 item (menu atau paket)
        $hasItems    = !empty($validated['items']);
        $hasPackages = !empty($validated['packages']);

        if (!$hasItems && !$hasPackages) {
            return back()->with('error', 'Minimal pilih 1 menu atau 1 paket.')->withInput();
        }

        DB::beginTransaction();

        try {
            $table = Table::lockForUpdate()->findOrFail($validated['table_id']);

            $calculatedSubtotal = 0;
            $orderItemsData     = [];  // untuk menu biasa
            $packageItemsData   = [];  // untuk paket

            // ── 1. PROSES MENU BIASA ──────────────────────────────────
            foreach (($validated['items'] ?? []) as $itemInput) {
                $menu = Menu::with('optionGroups.options')->findOrFail($itemInput['menu_id']);

                if (!$menu->is_available) {
                    throw new \Exception("Menu {$menu->name} tidak tersedia");
                }

                $itemPrice       = $menu->price;
                $itemOptionsData = [];

                if (!empty($itemInput['options'])) {
                    $optionsInput = json_decode($itemInput['options'], true);

                    if (!is_array($optionsInput)) {
                        throw new \Exception("Format options tidak valid");
                    }

                    foreach ($optionsInput as $groupId => $selectedOptions) {
                        $optionGroup = $menu->optionGroups->firstWhere('id', $groupId);
                        if (!$optionGroup) throw new \Exception("Option group tidak valid");

                        if ($optionGroup->pivot->is_required && empty($selectedOptions)) {
                            throw new \Exception("Option '{$optionGroup->name}' wajib dipilih");
                        }

                        if ($optionGroup->type === 'single' && count($selectedOptions) > 1) {
                            throw new \Exception("Hanya boleh pilih 1 untuk '{$optionGroup->name}'");
                        }

                        foreach ($selectedOptions as $optionData) {
                            if (isset($optionData['custom_value'])) {
                                if ($optionGroup->type !== 'text') throw new \Exception("Custom value tidak valid");
                                $itemOptionsData[] = [
                                    'option_group_id'   => $optionGroup->id,
                                    'option_group_name' => $optionGroup->name,
                                    'option_group_type' => $optionGroup->type,
                                    'option_id'         => null,
                                    'option_name'       => null,
                                    'option_price'      => 0,
                                    'custom_value'      => strip_tags($optionData['custom_value']),
                                ];
                                continue;
                            }

                            if (!isset($optionData['option_id'])) throw new \Exception("Option ID tidak valid");

                            $menuOption = $optionGroup->options->firstWhere('id', $optionData['option_id']);
                            if (!$menuOption) throw new \Exception("Option tidak ditemukan");

                            $itemPrice += $menuOption->extra_price;

                            $itemOptionsData[] = [
                                'option_group_id'   => $optionGroup->id,
                                'option_group_name' => $optionGroup->name,
                                'option_group_type' => $optionGroup->type,
                                'option_id'         => $menuOption->id,
                                'option_name'       => $menuOption->name,
                                'option_price'      => $menuOption->extra_price,
                                'custom_value'      => null,
                            ];
                        }
                    }
                }

                $itemSubtotal        = $itemPrice * $itemInput['qty'];
                $calculatedSubtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'menu_id'   => $menu->id,
                    'qty'       => $itemInput['qty'],
                    'price'     => $itemPrice,
                    'subtotal'  => $itemSubtotal,
                    'item_name' => $menu->name,
                    'item_type' => 'menu',
                    'options'   => $itemOptionsData,
                ];
            }

            // ── 2. PROSES PAKET BUNDLING ─────────────────────────────
            foreach (($validated['packages'] ?? []) as $pkgInput) {
                $package = PriceOffer::findOrFail($pkgInput['price_offer_id']);

                if (!$package->is_active) {
                    throw new \Exception("Paket {$package->name} tidak tersedia");
                }

                // Cek ketersediaan waktu jika paket punya batasan jam
                if (!$package->isAvailableNow()) {
                    throw new \Exception("Paket {$package->name} tidak tersedia di jam ini");
                }

                $pkgSubtotal         = $package->package_price * $pkgInput['qty'];
                $calculatedSubtotal += $pkgSubtotal;

                $packageItemsData[] = [
                    'price_offer_id' => $package->id,
                    'menu_id'        => null,
                    'item_name'      => $package->name,
                    'item_type'      => 'package',
                    'qty'            => $pkgInput['qty'],
                    'price'          => $package->package_price,
                    'subtotal'       => $pkgSubtotal,
                ];
            }

            // ── 3. VALIDASI TOTAL ────────────────────────────────────
            if ($calculatedSubtotal < 1000) {
                throw new \Exception("Minimal order Rp 1.000");
            }
            if ($calculatedSubtotal > 100000000) {
                throw new \Exception("Maksimal order Rp 100.000.000");
            }

            // ── 4. HITUNG PAJAK (dari database) ──────────────────────
            $pajak = Pajak::first();
            if (!$pajak) throw new \Exception("Data pajak tidak ditemukan. Hubungi administrator.");

            $ppnRate        = $pajak->ppn ?? 10;
            $serviceFeeRate = $pajak->service_fee ?? 50;
            $taxAmount      = round($calculatedSubtotal * ($ppnRate / 100), 2);
            $serviceFee     = round($taxAmount * ($serviceFeeRate / 100), 2);
            $discountAmount = 0;
            $totalPrice     = $calculatedSubtotal + $taxAmount + $serviceFee - $discountAmount;

            // ── 5. BUAT ORDER ─────────────────────────────────────────
            $order = Order::create([
                'table_id'        => $validated['table_id'],
                'customer_name'   => strip_tags($validated['customer_name']),
                'customer_email'  => $validated['customer_email'] ?? null,
                'customer_phone'  => $validated['customer_phone'] ?? null,
                'status'          => 'pending',
                'subtotal'        => $calculatedSubtotal,
                'tax_amount'      => $taxAmount,
                'service_fee'     => $serviceFee,
                'discount_amount' => $discountAmount,
                'total_price'     => $totalPrice,
            ]);

            // ── 6. INSERT ORDER ITEMS (menu biasa) ───────────────────
            foreach ($orderItemsData as $itemData) {
                $orderItem = $order->items()->create([
                    'menu_id'   => $itemData['menu_id'],
                    'item_name' => $itemData['item_name'],
                    'item_type' => $itemData['item_type'],
                    'qty'       => $itemData['qty'],
                    'price'     => $itemData['price'],
                    'subtotal'  => $itemData['subtotal'],
                ]);

                foreach ($itemData['options'] as $optionData) {
                    $orderItem->options()->create($optionData);
                }
            }

            // ── 7. INSERT ORDER ITEMS (paket) ────────────────────────
            foreach ($packageItemsData as $pkgData) {
                $order->items()->create([
                    'price_offer_id' => $pkgData['price_offer_id'],
                    'menu_id'        => null,
                    'item_name'      => $pkgData['item_name'],
                    'item_type'      => 'package',
                    'qty'            => $pkgData['qty'],
                    'price'          => $pkgData['price'],
                    'subtotal'       => $pkgData['subtotal'],
                ]);
            }

            // ── 8. UPDATE STATUS TABLE ────────────────────────────────
            $table->update(['status' => 'occupied']);

            $order->refresh();

            DB::commit();

            Log::info('Order created', [
                'order_code'   => $order->order_code,
                'subtotal'     => $calculatedSubtotal,
                'menu_items'   => count($orderItemsData),
                'pkg_items'    => count($packageItemsData),
                'table_id'     => $table->id,
                'ip'           => $request->ip(),
            ]);

            broadcast(new OrderCreated($order->load('table')));

            $encryptedCode = Crypt::encryptString($order->order_code);

            return redirect()->route('order.confirmation', ['orderCode' => $encryptedCode]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'ip'    => $request->ip(),
            ]);
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ✅ HALAMAN KONFIRMASI ORDER
     */
    public function confirmation($orderCode)
    {
        // try {
        //     $decoded = Crypt::decryptString(urldecode($orderCode));
        // } catch (\Exception $e) {
        //     abort(404);
        // }
        // ✅ Sesudah — langsung decrypt saja
        try {
            $decoded = Crypt::decryptString($orderCode);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak valid.',
            ], 404);
        }

        // $order = Order::with(['items.menu', 'items.options', 'table'])
        //     ->where('order_code', $decoded)
        //     ->firstOrFail();
        $order = Order::with(['items.menu', 'items.options', 'items.priceOffer.items', 'table'])
            ->where('order_code', $decoded)
            ->firstOrFail();


        // return view('order.confirmation', compact('order'));
        return view('order.confirmation', [
            'order'       => $order,
            'orderCode'   => $orderCode, // ← pass encrypted code yang sudah valid
        ]);
    }

    // public function uploadBukti(Request $request, $orderCode)
    // {
    //     // ✅ Validasi input
    //     $validator = Validator::make($request->all(), [
    //         'buktiPembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //         'notePembayaran' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }

    //     // ✅ Sesudah — langsung decrypt saja
    //     try {
    //         $decoded = Crypt::decryptString($orderCode);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Order tidak valid.',
    //         ], 404);
    //     }

    //     // ✅ Cari order berdasarkan order_code (bukan id)
    //     $order = Order::where('order_code', $decoded)->firstOrFail();

    //     // ✅ Upload file
    //     if ($request->hasFile('buktiPembayaran')) {
    //         $file = $request->file('buktiPembayaran');

    //         // nama file unik
    //         $filename = time() . '_' . $file->getClientOriginalName();

    //         // simpan ke storage/app/public/bukti
    //         $path = $file->storeAs('bukti', $filename, 'public');

    //         // simpan path ke database
    //         $order->buktiPembayaran = $path;
    //     }

    //     // simpan catatan
    //     $order->notePembayaran = $request->notePembayaran;
    //     $order->save();

    //     // return back()->with('success', 'Bukti pembayaran berhasil diupload');
    //     // ✅ Return JSON bukan back()
    //     return response()->json(['success' => true]);
    // }

    public function uploadBukti(Request $request, $orderCode)
    {
        // ✅ Validasi — notePembayaran nullable, return JSON jika gagal
        $validator = Validator::make($request->all(), [
            'buktiPembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            // 'notePembayaran'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // ✅ Decrypt langsung tanpa urldecode
        try {
            $decoded = Crypt::decryptString($orderCode);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak valid.',
            ], 404);
        }

        $order = Order::where('order_code', $decoded)->firstOrFail();

        if ($request->hasFile('buktiPembayaran')) {
            $file     = $request->file('buktiPembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('bukti', $filename, 'public');
            $order->buktiPembayaran = $path;
        }

        $order->notePembayaran = 'Sudah Transfer Atas Nama ' . $order->customer_name . ' Pada Jam ' . Carbon::now()->format('H:i') . ' WIB';

        $order->status = 'paid';
        $order->save();

        broadcast(new PaymentUploaded($order));

        return response()->json(['success' => true]);
    }

    /**
     * POST /order/add-package
     *
     * Menambahkan paket bundling ke order yang sedang aktif.
     * Dipanggil dari tombol "+" di carousel paket menu QR.
     *
     * Request body:
     *   - price_offer_id : int   (ID paket, wajib)
     *   - qty            : int   (jumlah paket, default 1)
     *
     * Response JSON:
     *   - success    : bool
     *   - message    : string
     *   - cart_count : int   (total item di order, untuk update badge)
     *   - order_item : array (data item yang baru ditambahkan)
     */
    // public function addPackage(Request $request)
    // {
    //     $request->validate([
    //         'price_offer_id' => 'required|exists:price_offers,id',
    //         'qty'            => 'nullable|integer|min:1|max:10',
    //     ]);

    //     // ── Ambil paket ──
    //     $package = PriceOffer::findOrFail($request->price_offer_id);

    //     // Pastikan paket aktif
    //     if (! $package->is_active) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Paket ini tidak tersedia.',
    //         ], 422);
    //     }

    //     $qty = $request->qty ?? 1;

    //     // ── Ambil order aktif dari session / query parameter ──
    //     // Sesuaikan dengan cara project Anda menyimpan order aktif.
    //     // Contoh umum: order ID disimpan di session berdasarkan table
    //     // $tableId = $request->query('table')
    //     //     ?? session('active_table_id');
    //     $encryptedTableId = $request->query('table');

    //     if (!$encryptedTableId) {
    //         return redirect()->back()->with('error', 'Table tidak valid');
    //     }

    //     $tableId = Crypt::decryptString($encryptedTableId);

    //     // Validasi table exists dan available
    //     $table = Table::findOrFail($tableId);

    //     // Cari order dengan status pending untuk meja ini
    //     $order = Order::where('table_id', $table->id)
    //         ->first();

    //     // Jika belum ada order aktif, buat baru
    //     if (! $order) {
    //         $year        = now()->year;
    //         $lastNumber  = Order::where('order_year', $year)->max('order_number') ?? 0;
    //         $orderNumber = $lastNumber + 1;

    //         $order = Order::create([
    //             'order_year'     => $year,
    //             'order_number'   => $orderNumber,
    //             'order_code'     => 'ORD-' . $year . '-' . str_pad($orderNumber, 4, '0', STR_PAD_LEFT),
    //             // 'customer_name'  => 'Walk-in',
    //             'table_id'       => $tableId,
    //             'status'         => 'pending',
    //             'subtotal'       => 0,
    //             'tax_amount'     => 0,
    //             'discount_amount' => 0,
    //             'service_fee'    => 0,
    //             'total_price'    => 0,
    //         ]);
    //     }

    //     // ── Cek apakah paket yang sama sudah ada di order ──
    //     // Jika ada: tambah qty-nya saja
    //     $existing = OrderItem::where('order_id', $order->id)
    //         ->where('price_offer_id', $package->id)
    //         ->where('item_type', 'package')
    //         ->first();

    //     if ($existing) {
    //         $newQty = $existing->qty + $qty;
    //         $existing->update([
    //             'qty'      => $newQty,
    //             'subtotal' => $package->package_price * $newQty,
    //         ]);
    //         $orderItem = $existing->fresh();
    //     } else {
    //         // Buat order_item baru untuk paket ini
    //         $orderItem = OrderItem::create([
    //             'order_id'       => $order->id,
    //             'price_offer_id' => $package->id,
    //             'menu_id'        => null,
    //             'item_name'      => $package->name,
    //             'item_type'      => 'package',
    //             'qty'            => $qty,
    //             'price'          => $package->package_price,
    //             'subtotal'       => $package->package_price * $qty,
    //         ]);
    //     }

    //     // ── Recalculate total order ──
    //     $this->recalculateOrder($order);

    //     // ── Hitung cart_count: total semua item di order ini ──
    //     $cartCount = OrderItem::where('order_id', $order->id)->sum('qty');

    //     return response()->json([
    //         'success'    => true,
    //         'message'    => "{$package->name} berhasil ditambahkan.",
    //         'cart_count' => $cartCount,
    //         'order_item' => [
    //             'id'         => $orderItem->id,
    //             'item_name'  => $orderItem->item_name,
    //             'qty'        => $orderItem->qty,
    //             'price'      => $orderItem->price,
    //             'subtotal'   => $orderItem->subtotal,
    //             'item_type'  => $orderItem->item_type,
    //         ],
    //     ]);
    // }

    // /**
    //  * Hitung ulang subtotal, tax, dan total_price di order.
    //  */
    // private function recalculateOrder(Order $order): void
    // {
    //     $subtotal        = OrderItem::where('order_id', $order->id)->sum('subtotal');
    //     $discountAmount  = $order->discount_amount ?? 0;
    //     $afterDiscount   = $subtotal - $discountAmount;
    //     $taxAmount       = $afterDiscount * 0.10; // 10% — sesuaikan
    //     $serviceFee      = $order->service_fee ?? 0;
    //     $totalPrice      = $afterDiscount + $taxAmount + $serviceFee;

    //     $order->update([
    //         'subtotal'    => $subtotal,
    //         'tax_amount'  => $taxAmount,
    //         'total_price' => $totalPrice,
    //     ]);
    // }
}

<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Category;
use App\Models\Menu;
use App\Models\OptionGroup;
use App\Models\Order;
use App\Models\Pajak;
use App\Models\Table;
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
    public function show(Order $order)
    {
        $order->load([
            'table:id,kode_table',
            'items.menu'
        ]);

        return response()->json([
            'order_code'    => $order->order_code,
            'tableKode'     => optional($order->table)->kode_table,
            'customer_name' => $order->customer_name,
            'status'        => $order->status,
            'total_price'   => $order->total_price,
            'items'         => $order->items,
        ]);
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
        //
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

            return view('order.menu', compact('table', 'data', 'categories'));
        } catch (\Exception $e) {
            Log::error('Menu page error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * ✅ STORE ORDER - DENGAN VALIDASI KETAT
     */
    public function store(Request $request)
    {
        // VALIDASI INPUT
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|min:3|max:100',
            'customer_email' => 'nullable|email|max:100',
            'customer_phone' => 'nullable|regex:/^[0-9]{10,15}$/|max:15',
            'items' => 'required|array|min:1|max:50',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1|max:100',
            // 'items.*.notes' => 'nullable|string|max:500',
            'items.*.options' => 'nullable|json|max:5000',
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi',
            'customer_name.min' => 'Nama minimal 3 karakter',
            'customer_email.email' => 'Format email tidak valid',
            'customer_phone.regex' => 'Format nomor telepon tidak valid (10-15 digit)',
            'items.required' => 'Minimal pilih 1 menu',
            'items.*.qty.min' => 'Jumlah minimal 1',
            'items.*.qty.max' => 'Jumlah maksimal 100 per item',
        ]);

        DB::beginTransaction();

        try {
            // ✅ VALIDASI TABLE MASIH AVAILABLE
            $table = Table::lockForUpdate()->findOrFail($validated['table_id']);

            // if (!$table->is_available) {
            //     throw new \Exception("Table sudah tidak tersedia");
            // }

            // ✅ HITUNG ULANG SEMUA HARGA DI SERVER
            $calculatedSubtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $itemInput) {
                // Load menu dengan relationships
                $menu = Menu::with('optionGroups.options')
                    ->findOrFail($itemInput['menu_id']);

                // Cek menu available
                if (!$menu->is_available) {
                    throw new \Exception("Menu {$menu->name} tidak tersedia");
                }

                // ✅ MULAI DARI HARGA DASAR MENU (DARI DATABASE!)
                $itemPrice = $menu->price;
                $itemOptionsData = [];

                // ✅ PROSES & VALIDASI OPTIONS
                if (!empty($itemInput['options'])) {
                    $optionsInput = json_decode($itemInput['options'], true);

                    if (!is_array($optionsInput)) {
                        throw new \Exception("Format options tidak valid");
                    }

                    foreach ($optionsInput as $groupId => $selectedOptions) {
                        // Validasi option group ada di menu
                        $optionGroup = $menu->optionGroups->firstWhere('id', $groupId);

                        if (!$optionGroup) {
                            throw new \Exception("Option group tidak valid");
                        }

                        // Validasi required options
                        if ($optionGroup->pivot->is_required && empty($selectedOptions)) {
                            throw new \Exception("Option '{$optionGroup->name}' wajib dipilih");
                        }

                        // Validasi single choice (hanya 1 pilihan)
                        if ($optionGroup->type === 'single' && count($selectedOptions) > 1) {
                            throw new \Exception("Hanya boleh pilih 1 untuk '{$optionGroup->name}'");
                        }

                        // Validasi multiple choice limit
                        if ($optionGroup->type === 'multiple' && count($selectedOptions) > 10) {
                            throw new \Exception("Maksimal 10 pilihan untuk '{$optionGroup->name}'");
                        }

                        // Proses setiap option
                        foreach ($selectedOptions as $optionData) {
                            // Handle custom text input
                            if (isset($optionData['custom_value'])) {
                                if ($optionGroup->type !== 'text') {
                                    throw new \Exception("Custom value tidak valid");
                                }

                                $itemOptionsData[] = [
                                    'option_group_id' => $optionGroup->id,
                                    'option_group_name' => $optionGroup->name,
                                    'option_group_type' => $optionGroup->type,
                                    'option_id' => null,
                                    'option_name' => null,
                                    'option_price' => 0,
                                    'custom_value' => strip_tags($optionData['custom_value']),
                                ];
                                continue;
                            }

                            // Validasi option_id exists
                            if (!isset($optionData['option_id'])) {
                                throw new \Exception("Option ID tidak valid");
                            }

                            // ✅ AMBIL OPTION DARI DATABASE (BUKAN DARI CLIENT!)
                            $menuOption = $optionGroup->options
                                ->firstWhere('id', $optionData['option_id']);

                            if (!$menuOption) {
                                throw new \Exception("Option tidak ditemukan");
                            }

                            // ✅ TAMBAHKAN EXTRA PRICE (DARI DATABASE!)
                            $itemPrice += $menuOption->extra_price;

                            // Simpan data option untuk database
                            $itemOptionsData[] = [
                                'option_group_id' => $optionGroup->id,
                                'option_group_name' => $optionGroup->name,
                                'option_group_type' => $optionGroup->type,
                                'option_id' => $menuOption->id,
                                'option_name' => $menuOption->name,
                                'option_price' => $menuOption->extra_price,
                                'custom_value' => null,
                            ];
                        }
                    }
                }

                // ✅ VALIDASI HARGA MASUK AKAL
                if ($itemPrice < 0 || $itemPrice > 10000000) {
                    throw new \Exception("Harga tidak valid untuk menu {$menu->name}");
                }

                // Hitung subtotal item
                $itemSubtotal = $itemPrice * $itemInput['qty'];
                $calculatedSubtotal += $itemSubtotal;

                // Simpan data untuk insert nanti
                $orderItemsData[] = [
                    'menu_id' => $menu->id,
                    'qty' => $itemInput['qty'],
                    'price' => $itemPrice,
                    'subtotal' => $itemSubtotal,
                    'options' => $itemOptionsData,
                ];
            }

            // ✅ VALIDASI TOTAL AKHIR
            if ($calculatedSubtotal < 1000) {
                throw new \Exception("Minimal order Rp 1.000");
            }

            if ($calculatedSubtotal > 100000000) {
                throw new \Exception("Maksimal order Rp 100.000.000");
            }

            // // ✅ HITUNG TAX, SERVICE FEE, DISCOUNT
            // $tax = Pajak::first();
            // $taxAmount = round($calculatedSubtotal * ($tax->ppn / 100), 2); // Tax from database
            // $serviceFee = round($taxAmount * ($tax->service_fee / 100), 2); // Service fee from database
            // // $serviceFee = round($calculatedSubtotal * ($tax->service_fee / 100), 2); // Service fee from database
            // $discountAmount = 0; // Bisa dikembangkan dengan promo code

            // $totalPrice = $calculatedSubtotal + $taxAmount + $serviceFee - $discountAmount;

            // ✅ LEBIH AMAN
            $pajak = Pajak::first();

            if (!$pajak) {
                // Fallback jika data pajak tidak ada
                throw new \Exception("Data pajak tidak ditemukan. Hubungi administrator.");
            }

            // Validasi field tidak null
            $ppnRate = $pajak->ppn ?? 10; // Default 10% jika null
            $serviceFeeRate = $pajak->service_fee ?? 50; // Default 50% jika null

            $taxAmount = round($calculatedSubtotal * ($ppnRate / 100), 2);
            $serviceFee = round($taxAmount * ($serviceFeeRate / 100), 2);
            $discountAmount = 0;

            $totalPrice = $calculatedSubtotal + $taxAmount + $serviceFee - $discountAmount;

            // ✅ CREATE ORDER
            $order = Order::create([
                'table_id' => $validated['table_id'],
                'customer_name' => strip_tags($validated['customer_name']),
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'status' => 'pending',
                'subtotal' => $calculatedSubtotal,
                'tax_amount' => $taxAmount,
                'service_fee' => $serviceFee,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
            ]);

            // ✅ CREATE ORDER ITEMS & OPTIONS
            foreach ($orderItemsData as $itemData) {
                $orderItem = $order->items()->create([
                    'menu_id' => $itemData['menu_id'],
                    'qty' => $itemData['qty'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemData['subtotal'],
                ]);

                // Insert options jika ada
                if (!empty($itemData['options'])) {
                    foreach ($itemData['options'] as $optionData) {
                        $orderItem->options()->create($optionData);
                    }
                }
            }

            // ✅ UPDATE TABLE STATUS
            $table->update(['status' => 'occupied']);

            // ✅ REFRESH ORDER UNTUK MENDAPATKAN order_code YANG SUDAH DI-GENERATE
            $order->refresh();

            DB::commit();

            // ✅ LOG UNTUK AUDIT
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'total' => $totalPrice,
                'table_id' => $table->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // ✅ LOG SEBELUM BROADCAST
            Log::info('🔔 BROADCASTING ORDER', [
                'order_code' => $order->order_code,
                'order_id' => $order->id,
                'table' => $order->table->name ?? 'N/A'
            ]);

            // 🔔 BROADCAST EVENT - Kirim notifikasi real-time ke kasir
            // broadcast(new OrderCreated($order->load('table')))->toOthers();
            broadcast(new OrderCreated($order->load('table')));

            Log::info('✅ BROADCAST BERHASIL');

            // ✅ REDIRECT DENGAN order_code (BUKAN order->id)
            return redirect()
                ->route('order.confirmation', ['orderCode' => $order->order_code])
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'input' => $request->except(['_token']),
            ]);

            return back()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ✅ HALAMAN KONFIRMASI ORDER
     */
    public function confirmation($orderCode)
    {
        $order = Order::with(['items.menu', 'items.options', 'table'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('order.confirmation', compact('order'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\OptionGroup;
use App\Models\Table;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $isAvailable = $request->is_available; // new filter
        $categoryId = $request->category_id;

        // $categories = Category::where('parent_id', null)->orderBy('name')->get();
        // $categories = Category::whereNull('parent_id')
        //     ->with('children')
        //     ->orderBy('name')
        //     ->get();

        // $data = Menu::with('category.parent')
        //     ->when($categoryId, function ($q) use ($categoryId) {
        //         $q->whereHas('category', function ($cat) use ($categoryId) {
        //             $cat->where('id', $categoryId)
        //                 ->orWhere('parent_id', $categoryId);
        //         });
        //     })
        //     ->when($search, function ($q) use ($search) {
        //         $q->where(function ($query) use ($search) {
        //             $query->where('name', 'like', "%{$search}%")
        //                 ->orWhereHas('category', function ($cat) use ($search) {
        //                     $cat->where('name', 'like', "%{$search}%")
        //                         ->orWhereHas('parent', function ($parent) use ($search) {
        //                             $parent->where('name', 'like', "%{$search}%");
        //                         });
        //                 });
        //         });
        //     })
        //     ->when($request->filled('is_available'), function ($q) use ($isAvailable) {
        //         $q->where('is_available', (bool) $isAvailable);
        //     })
        //     ->orderBy('id', 'desc')
        //     ->cursorPaginate(8)
        //     ->withQueryString();

        $categories = Category::whereNull('parent_id')
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
            ->when($request->filled('is_available'), function ($q) use ($isAvailable) {
                $q->where('is_available', (bool) $isAvailable);
            })
            ->orderBy('id', 'desc')
            ->cursorPaginate(8)
            ->withQueryString();


        // $data = Menu::with('category')
        //     ->when(
        //         $categoryId,
        //         fn($q) =>
        //         $q->where('category_id', $categoryId)
        //     )
        //     ->when($search, function ($q) use ($search) {
        //         $q->where('name', 'like', "%{$search}%")
        //             ->orWhereHas('category', function ($cat) use ($search) {
        //                 $cat->where('name', 'like', "%{$search}%");
        //             });
        //     })
        //     ->when($request->filled('is_available'), function ($q) use ($isAvailable) {
        //         // pastikan 0 tetap terbaca, cast ke boolean
        //         $q->where('is_available', (bool) $isAvailable);
        //     })
        //     ->orderBy('id', 'desc')
        //     ->cursorPaginate(8)
        //     ->withQueryString(); // ⭐ PENTING agar query string tetap terjaga



        if ($request->ajax()) {
            return response(
                view('admin.menus._items', compact('data', 'categories'))->render()
            )->header(
                'X-Cursor',
                optional($data->nextCursor())->encode()
            );
        }

        return view('admin.menus.index', compact('data', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = FacadesValidator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
            'is_available' => 'required|boolean',
            'description' => 'required|string|max:1000',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Handle image upload
        $imagePath = $request->file('image')->store('menus', 'public');

        Menu::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'is_available' => $request->is_available,
            'description' => $request->description,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $optionGroups = OptionGroup::orderBy('name')->get();

        $menu->load([
            'menuOptionGroups.optionGroup.options'
        ]);

        return view('admin.menus.show', compact('menu', 'optionGroups'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validate = FacadesValidator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'required|boolean',
            'description' => 'required|string|max:1000',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Handle image upload with unlinking old image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('menus', 'public');
            $menu->image = $imagePath;

            $menu->save();
        }

        $menu->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'is_available' => $request->is_available,
            'description' => $request->description,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        // Delete image from storage
        // if ($menu->image && Storage::disk('public')->exists($menu->image)) {
        //     Storage::disk('public')->delete($menu->image);
        // }

        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }


    // ORDER PAGE FOR CUSTOMERS
    // public function orderPage(Request $request)
    // {
    //     try {
    //         $encryptedTable = $request->query('table');
    //         abort_if(!$encryptedTable, 404);

    //         $tableId = Crypt::decryptString($encryptedTable);
    //         $table = Table::findOrFail($tableId);

    //         $categories = Category::orderBy('id')->get();

    //         $menuData = Menu::where('is_available', true)
    //             ->get()
    //             ->map(function ($menu) {
    //                 return [
    //                     'id' => $menu->id,
    //                     'name' => strtolower($menu->name),
    //                     'price' => $menu->price,
    //                     'category_id' => (string) $menu->category_id,
    //                 ];
    //             })
    //             ->keyBy('id');

    //         $menus = Menu::with('category')
    //             ->where('is_available', true)
    //             ->get();

    //         return view('order.menu', compact(
    //             'table',
    //             'menus',
    //             'categories',
    //             'menuData'
    //         ));
    //     } catch (DecryptException $e) {
    //         abort(404);
    //     }
    // $categories = Category::orderBy('id')->get();

    // // Ambil menu dengan relasi category
    // $menus = Menu::with('category')
    //     ->where('is_available', true)
    //     ->cursorPaginate(8);

    // return view('order.menu', compact(
    //     'table',
    //     'menus',
    //     'categories'
    // ));
    // }
    // public function orderPage(Request $request)
    // {
    //     try {
    //         $encryptedTable = $request->query('table');
    //         abort_if(!$encryptedTable, 404);

    //         $tableId = Crypt::decryptString($encryptedTable);
    //         $table = Table::findOrFail($tableId);

    //         $search = $request->search;
    //         $isAvailable = $request->is_available; // new filter
    //         $categoryId = $request->category_id;

    //         $categories = Category::orderBy('name')->get();

    //         $data = Menu::with('category')
    //             ->when(
    //                 $categoryId,
    //                 fn($q) =>
    //                 $q->where('category_id', $categoryId)
    //             )
    //             ->when($search, function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                     ->orWhereHas('category', function ($cat) use ($search) {
    //                         $cat->where('name', 'like', "%{$search}%");
    //                     });
    //             })
    //             // ->when($request->filled('is_available'), function ($q) use ($isAvailable) {
    //             //     // pastikan 0 tetap terbaca, cast ke boolean
    //             //     $q->where('is_available', (bool) $isAvailable);
    //             // })
    //             ->where('is_available', true) // hanya yang tersedia
    //             ->orderBy('id', 'desc')
    //             ->cursorPaginate(8)
    //             ->withQueryString(); // ⭐ PENTING agar query string tetap terjaga

    //         if ($request->ajax()) {
    //             return response(
    //                 view('order._items', compact('data', 'categories'))->render()
    //             )->header(
    //                 'X-Cursor',
    //                 optional($data->nextCursor())->encode()
    //             );
    //         }

    //         return view('order.menu', compact('table', 'data', 'categories'));
    //     } catch (DecryptException $e) {
    //         abort(404);
    //     }
    // }

    // $data = Menu::with('category')
    //     ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
    //     ->when($search, function ($q) use ($search) {
    //         $q->where('name', 'like', "%{$search}%")
    //             ->orWhereHas('category', function ($cat) use ($search) {
    //                 $cat->where('name', 'like', "%{$search}%");
    //             });
    //     })
    //     ->where('is_available', true)
    //     ->orderBy('category.name') // urutkan berdasarkan kategori terlebih dahulu
    //     ->orderBy('id', 'desc')
    //     ->cursorPaginate(8)
    //     ->withQueryString();

    public function orderPage(Request $request)
    {
        try {
            $encryptedTable = $request->query('table');
            abort_if(!$encryptedTable, 404);

            $tableId = Crypt::decryptString($encryptedTable);
            $table = Table::findOrFail($tableId);

            $search = $request->search;
            $categoryId = $request->category_id;

            // $categories = Category::orderBy('name')->get();
            $categories = Category::whereHas('menus', function ($query) {
                $query->where('is_available', true);
            })
                ->orderBy('name')
                ->get();

            $data = Menu::with('category')
                ->join('categories', 'menus.category_id', '=', 'categories.id')
                ->select('menus.*') // penting! agar tidak konflik kolom
                ->when($categoryId, fn($q) => $q->where('menus.category_id', $categoryId))
                ->when($search, function ($q) use ($search) {
                    $q->where('menus.name', 'like', "%{$search}%")
                        ->orWhere('categories.name', 'like', "%{$search}%");
                })
                ->where('menus.is_available', true)
                ->orderBy('categories.name', 'asc')  // urutkan berdasarkan nama kategori
                ->orderBy('menus.id', 'desc')        // kemudian urutkan berdasarkan menu ID
                ->cursorPaginate(6)
                ->withQueryString();

            if ($request->ajax()) {
                return response(
                    view('order._items', compact('data'))->render()
                )->header(
                    'X-Cursor',
                    optional($data->nextCursor())->encode()
                );
            }

            return view('order.menu', compact('table', 'data', 'categories'));
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function getMenuOptions(Menu $menu)
    {
        $menu->load([
            'optionGroups' => function ($query) {
                $query->orderBy('menu_option_groups.sort_order');
            },
            'optionGroups.options' => function ($query) {
                $query->where('is_active', true);
            }
        ]);

        return response()->json([
            'id' => $menu->id,
            'name' => $menu->name,
            'price' => $menu->price,
            'option_groups' => $menu->optionGroups
        ]);
    }
}

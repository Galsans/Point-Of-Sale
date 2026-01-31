<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Category::query()
            ->select(['id', 'name'])
            ->orderBy('id') // wajib unique
            ->cursorPaginate(8);

        // AJAX request (infinite scroll)
        if ($request->ajax()) {
            return response()
                ->view('admin.categories._item', compact('data'))
                ->header('X-Cursor', optional($data->nextCursor())->encode());
        }

        return view('admin.categories.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = FacadesValidator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        Category::create($validate->validated());
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, $id)
    {
        $data = Category::find($id);
        return view('categories.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, $id)
    {
        $data = Category::find($id);
        return view('categories.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validate = FacadesValidator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $category->id,
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $category->update($validate->validated());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }



    /**     * Search for categories based on a query.
     * Api function category for select
     */

    // public function search(Request $request)
    // {
    //     try {
    //         $search = $request->get('search', '');
    //         // $cursor = $request->get('cursor', null);

    //         $query = Category::query();

    //         // Filter berdasarkan pencarian
    //         if (!empty($search)) {
    //             $query->where('name', 'LIKE', "%{$search}%");
    //         }

    //         // Cursor Pagination dengan orderBy wajib
    //         $categories = $query->orderBy('id')
    //             ->cursorPaginate(10)
    //             ->withQueryString(); // ⭐ PENTING agar query string tetap terjaga

    //         // return response()->json([
    //         //     // 'data' => $categories->items(),
    //         //     // 'next_cursor' => $categories->nextCursor(),
    //         //     // 'has_more' => $categories->hasMorePages(),
    //         //     // 'per_page' => $categories->perPage()
    //         //     'data' => $categories,
    //         // ]);
    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'data' => $categories,
    //             ])->header(
    //                 'X-Cursor',
    //                 optional($categories->nextCursor())->encode()
    //             );
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Category search error: ' . $e->getMessage());

    //         return response()->json([
    //             'data' => [],
    //             'next_cursor' => null,
    //             'has_more' => false,
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $categories = Category::when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })
            ->orderBy('id')
            ->paginate(10);

        return response()->json([
            'data' => $categories->items(),
            'pagination' => [
                'more' => $categories->hasMorePages()
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MenuOptionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuOptionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MenuOptionGroup::cursorPaginate(8);
        return view('admin.menuOptionGroup.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.menuOptionGroup.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'menu_id' => 'required|exists:menus,id',
            'option_group_id' => 'required|exists:option_groups,id',
            'is_required' => 'required|boolean',
            'min_choice' => 'nullable|integer|min:0',
            'max_choice' => 'nullable|integer|min:0',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $menuId = $request->menu_id;

        // 🔥 Ambil sort_order terakhir dalam menu yang sama
        $lastSortOrder = MenuOptionGroup::where('menu_id', $menuId)
            ->max('sort_order');

        $nextSortOrder = ($lastSortOrder ?? 0) + 1;

        MenuOptionGroup::create([
            'menu_id' => $menuId,
            'option_group_id' => $request->option_group_id,
            'is_required' => $request->is_required,
            'min_choice' => $request->min_choice,
            'max_choice' => $request->max_choice,
            'sort_order' => $nextSortOrder,
        ]);

        return redirect()->back()->with('success', 'Menu Option Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuOptionGroup $menuOptionGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuOptionGroup $menuOptionGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, MenuOptionGroup $menuOptionGroup)
    {
        $validate = Validator::make($request->all(), [
            'menu_id' => 'required|exists:menus,id',
            'option_group_id' => 'required|exists:option_groups,id',
            'is_required' => 'required|boolean',
            'min_choice' => 'nullable|integer|min:0',
            'max_choice' => 'nullable|integer|min:0',
            'sort_order' => 'required|integer|min:1',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        DB::transaction(function () use ($request, $menuOptionGroup) {

            $menuId = $request->menu_id;
            $newOrder = $request->sort_order;
            $oldOrder = $menuOptionGroup->sort_order;

            // Jika menu berubah → reset urutan
            if ($menuId != $menuOptionGroup->menu_id) {

                // Ambil urutan terakhir di menu baru
                $lastOrder = MenuOptionGroup::where('menu_id', $menuId)->max('sort_order');

                $menuOptionGroup->update([
                    'menu_id' => $menuId,
                    'option_group_id' => $request->option_group_id,
                    'is_required' => $request->is_required,
                    'min_choice' => $request->min_choice,
                    'max_choice' => $request->max_choice,
                    'sort_order' => ($lastOrder ?? 0) + 1,
                ]);

                return;
            }

            // Jika sort_order tidak berubah
            if ($newOrder == $oldOrder) {
                $menuOptionGroup->update($request->except('sort_order'));
                return;
            }

            // 🔼 Naik (3 ➜ 1)
            if ($newOrder < $oldOrder) {
                MenuOptionGroup::where('menu_id', $menuId)
                    ->whereBetween('sort_order', [$newOrder, $oldOrder - 1])
                    ->where('id', '!=', $menuOptionGroup->id)
                    ->increment('sort_order');
            }

            // 🔽 Turun (1 ➜ 3)
            if ($newOrder > $oldOrder) {
                MenuOptionGroup::where('menu_id', $menuId)
                    ->whereBetween('sort_order', [$oldOrder + 1, $newOrder])
                    ->where('id', '!=', $menuOptionGroup->id)
                    ->decrement('sort_order');
            }

            // Update data utama
            $menuOptionGroup->update([
                'menu_id' => $menuId,
                'option_group_id' => $request->option_group_id,
                'is_required' => $request->is_required,
                'min_choice' => $request->min_choice,
                'max_choice' => $request->max_choice,
                'sort_order' => $newOrder,
            ]);
        });

        return redirect()->back()->with('success', 'Menu Option Group updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuOptionGroup $menuOptionGroup)
    {
        $menuOptionGroup->delete();
        return redirect()->back()->with('success', 'Menu Option Group deleted successfully.');
    }
}

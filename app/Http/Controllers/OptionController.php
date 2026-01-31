<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $optionGroupId = $request->option_group_id;

    //     $optionGroups = OptionGroup::orderBy('name')->get();

    //     $data = Option::query()
    //         // ->select(['id', 'name', 'option_group_id'])
    //         ->when(
    //             $optionGroupId,
    //             fn($q) =>
    //             $q->where('option_group_id', $optionGroupId)
    //         )
    //         ->orderBy('id')
    //         ->cursorPaginate(8)
    //         ->withQueryString(); // ⭐ penting


    //     if ($request->ajax()) {
    //         return response(
    //             view('admin.option._items', compact('data', 'optionGroups'))->render()
    //         )->header(
    //             'X-Cursor',
    //             optional($data->nextCursor())->encode()
    //         );
    //     }
    //     return view('admin.option.index', compact('data', 'optionGroups'));
    // }

    public function index(Request $request)
    {
        $optionGroupId = $request->option_group_id;

        $optionGroups = OptionGroup::orderBy('name')->get();

        // $data = Option::query()
        //     ->select(['id', 'name', 'option_group_id'])
        //     ->when($optionGroupId, function ($q) use ($optionGroupId) {
        //         $q->where('option_group_id', $optionGroupId);
        //     })
        //     // ⭐ ORDER STABIL UNTUK CURSOR
        //     ->orderBy('option_group_id')
        //     ->orderBy('id')
        //     ->cursorPaginate(8)
        //     ->withQueryString(); // ⭐ WAJIB

        // $data = Option::query()
        //     ->select(['id', 'name', 'option_group_id'])
        //     ->when($request->filled('option_group_id'), function ($q) use ($request) {
        //         $q->where('option_group_id', $request->option_group_id);
        //     })
        //     ->orderBy('option_group_id')
        //     ->orderBy('id')
        //     ->cursorPaginate(8)
        //     ->withQueryString();

        $data = Option::with('optionGroup')
            ->when(
                $optionGroupId,
                fn($q) =>
                $q->where('option_group_id', $optionGroupId)
            )
            ->orderBy('id')
            ->cursorPaginate(8)
            ->withQueryString(); // ⭐ PENTING agar query string tetap terjaga

        if ($request->ajax()) {
            return response(
                view('admin.option._items', compact('data', 'optionGroups'))->render()
            )->header(
                'X-Cursor',
                optional($data->nextCursor())->encode()
            );
        }

        return view('admin.option.index', compact('data', 'optionGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $optionGroup = OptionGroup::orderBy('name')->get();
        return view('admin.option.create', compact('optionGroup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'option_group_id' => 'required|exists:option_groups,id',
            'name' => 'required|string|max:255',
            'extra_price' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        Option::create($validate->validated());
        return redirect()->route('options.index')->with('success', 'Option created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Option $option)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Option $option)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Option $option)
    {
        $validate = Validator::make($request->all(), [
            'option_group_id' => 'required|exists:option_groups,id',
            'name' => 'required|string|max:255',
            'extra_price' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $option->update($validate->validated());
        return redirect()->route('options.index')->with('success', 'Option updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Option $option)
    {
        $option->delete();
        return redirect()->route('options.index')->with('success', 'Option deleted successfully.');
    }
}

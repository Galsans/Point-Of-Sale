<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OptionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = OptionGroup::cursorPaginate(8);
        return view('admin.optionGroup.index', compact('data'));
    }

    public function search(Request $request)
    {
        return OptionGroup::query()
            ->select('id', 'name')
            ->when(
                $request->filled('q'),
                fn($q) =>
                $q->where('name', 'like', "%{$request->q}%")
            )
            ->orderBy('id')
            ->cursorPaginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.optionGroup.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,multiple,text',
            'description' => 'nullable|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        OptionGroup::create($validate->validated());
        return redirect()->route('option-groups.index')->with('success', 'Option Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OptionGroup $optionGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OptionGroup $optionGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OptionGroup $optionGroup)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,multiple,text',
            'description' => 'nullable|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $optionGroup->update($validate->validated());
        return redirect()->route('option-groups.index')->with('success', 'Option Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OptionGroup $optionGroup)
    {
        $optionGroup->delete();
        return redirect()->route('option-groups.index')->with('success', 'Option Group deleted successfully.');
    }

    public function options($id)
    {
        $options = Option::where('option_group_id', $id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($options);
    }
}

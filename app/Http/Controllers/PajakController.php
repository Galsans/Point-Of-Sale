<?php

namespace App\Http\Controllers;

use App\Models\Pajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tax = Pajak::latest()->first();

        return view('admin.tax.index', compact('tax'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'ppn' => 'required|integer',
            'serviceFee' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return redirect()
                ->back()
                ->withErrors($validate)
                ->withInput();
        }

        Pajak::create([
            'ppn' => $request->ppn,
            'serviceFee' => $request->serviceFee,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pajak berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pajak $pajak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pajak $pajak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pajak $pajak)
    {
        $validate = Validator::make($request->all(), [
            'ppn' => 'required|integer',
            'serviceFee' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return redirect()
                ->back()
                ->withErrors($validate)
                ->withInput();
        }

        $pajak->update([
            'ppn' => $request->ppn,
            'serviceFee' => $request->serviceFee,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pajak berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pajak $pajak)
    {
        //
    }
}

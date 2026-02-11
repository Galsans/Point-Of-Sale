<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $floor = $request->floor;

        $data = Table::when($floor, function ($q) use ($floor) {
            $q->where('floor', $floor);
        })
            ->orderBy('id')
            ->cursorPaginate(8)
            ->withQueryString(); // PENTING

        if ($request->ajax()) {
            return response(
                view('admin.tables._items', compact('data'))->render()
            )->header(
                'X-Cursor',
                optional($data->nextCursor())->encode()
            );
        }

        return view('admin.tables.index', compact('data'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = FacadesValidator::make($request->all(), [
            'kode_table' => 'required|unique:tables,kode_table',
            'status' => 'required|in:available,occupied',
            'floor' => 'required|in:1,2,3',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $table = Table::create([
            'kode_table' => $request->kode_table,
            'status' => $request->status,
            // 'qr_code' => null,
            'floor' => $request->floor,
        ]);

        $url = route('order.by-table', $table->id);

        $qrPath = 'qrcodes/table-' . $table->id . '.png';

        Storage::disk('public')->put(
            $qrPath,
            QrCode::format('png')->size(300)->generate($url)
        );

        $table->update([
            'qr_code' => $qrPath
        ]);


        return redirect()->route('tables.index')->with('success', 'Table created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Table $table, $id)
    {
        $data = Table::find($id);
        return view('tables.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table, $id)
    {
        $data = Table::find($id);

        return view('admin.tables.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        $request->validate([
            'kode_table' => 'required|unique:tables,kode_table,' . $table->id,
            'status' => 'required|in:available,occupied',
            'floor' => 'required|in:1,2,3',
        ]);

        $table->update([
            'kode_table' => $request->kode_table,
            'status' => $request->status,
            'floor' => $request->floor,
        ]);

        return redirect()
            ->route('tables.index')
            ->with('success', 'Meja berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        if ($table->status === 'occupied') {
            return back()->with('error', 'Meja sedang digunakan dan tidak bisa dihapus.');
        }

        $table->delete();

        return redirect()->route('tables.index')->with('success', 'Table deleted successfully.');
    }
}

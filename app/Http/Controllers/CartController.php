<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CartController extends Controller
{
    // public function cart(Request $request)
    // {
    //     $tableCode = $request->query('table');
    //     $encryptedTableId = $request->query('table');

    //     if (!$encryptedTableId) {
    //         return redirect()->back()->with('error', 'Table tidak valid');
    //     }

    //     $tableId = Crypt::decryptString($encryptedTableId);

    //     // Validasi table exists dan available
    //     $table = Table::findOrFail($tableId);

    //     // $table = Table::where('kode_table', $tableCode)->firstOrFail();

    //     return view('order.cart', compact('table'));
    // }
    public function cart(Request $request)
    {
        $tableCode = $request->query('table');
        $encryptedTableId = $request->query('table');

        if (!$encryptedTableId) {
            return redirect()->back()->with('error', 'Table tidak valid');
        }

        $tableId = Crypt::decryptString($encryptedTableId);
        $table = Table::findOrFail($tableId);

        return view('order.cart', compact('table'));
        // TEST SEMENTARA - hapus setelah fix
        // dd($table->toArray(), view()->exists('order.cart'));
    }
}

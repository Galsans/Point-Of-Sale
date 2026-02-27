<?php

namespace App\Http\Controllers;

use App\Models\Category;

class LandingController extends Controller
{
    // public function page()
    // {
    //     // Ambil menu yang aktif, group by kategori
    //     $menuCategories = Category::with(['menus' => function ($q) {
    //         $q->where('is_available', true)->orderBy('name');
    //     }])->get();

    //     return view('welcome', compact('menuCategories'));
    // }
    public function page()
    {
        $menuCategories = Category::with([
            'menus' => function ($q) {
                $q->where('is_available', true)->orderBy('name');
            },
            'children.menus' => function ($q) {
                $q->where('is_available', true)->orderBy('name');
            },
        ])
            ->whereNull('parent_id')
            ->take(6)
            ->get();

        return view('welcome', compact('menuCategories'));
    }
}

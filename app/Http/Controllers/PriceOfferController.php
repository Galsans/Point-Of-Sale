<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\PriceOffer;
use App\Models\PriceOfferItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PriceOfferController extends Controller
{
    // ============================================================
    // ADMIN — Kelola paket bundling
    // ============================================================

    // public function index()
    // {
    //     $packages = PriceOffer::withCount('orderItems')
    //         ->orderBy('sort_order')
    //         ->get();

    //     return view('admin.priceOffer.index', compact('packages'));
    // }

    // public function create()
    // {
    //     $menus = Menu::active()->orderBy('category')->orderBy('name')->get()->groupBy('category');
    //     return view('admin.priceOffer.form', compact('menus'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'            => 'required|string|max:255',
    //         'description'     => 'nullable|string',
    //         'badge'           => 'nullable|string|max:50',
    //         'package_price'   => 'required|numeric|min:0',
    //         'category'        => 'nullable|string|max:100',
    //         'is_active'       => 'boolean',
    //         'sort_order'      => 'integer|min:0',
    //         'available_from'  => 'nullable|date_format:H:i',
    //         'available_until' => 'nullable|date_format:H:i|after:available_from',
    //         'image'           => 'nullable|image|max:2048',

    //         'items'              => 'required|array|min:1',
    //         'items.*.menu_id'    => 'required|exists:menus,id',
    //         'items.*.quantity'   => 'required|integer|min:1',
    //         'items.*.category'   => 'nullable|string',
    //         'items.*.sort_order' => 'nullable|integer',
    //     ]);

    //     DB::transaction(function () use ($request) {
    //         $imagePath = $request->hasFile('image')
    //             ? $request->file('image')->store('packages', 'public')
    //             : null;

    //         $package = PriceOffer::create([
    //             'name'            => $request->name,
    //             'description'     => $request->description,
    //             'badge'           => $request->badge,
    //             'package_price'   => $request->package_price,
    //             'image'           => $imagePath,
    //             'category'        => $request->category,
    //             'is_active'       => $request->boolean('is_active', true),
    //             'sort_order'      => $request->sort_order ?? 0,
    //             'available_from'  => $request->available_from,
    //             'available_until' => $request->available_until,
    //         ]);

    //         // Simpan items — snapshot nama & harga otomatis via Model booted()
    //         foreach ($request->items as $i => $item) {
    //             $package->items()->create([
    //                 'menu_id'    => $item['menu_id'],
    //                 'quantity'   => $item['quantity'],
    //                 'category'   => $item['category'] ?? null,
    //                 'sort_order' => $item['sort_order'] ?? $i,
    //                 // item_name & item_price di-set otomatis di PriceOfferItem::saving()
    //             ]);
    //         }
    //         // original_price dihitung otomatis via recalculateOriginalPrice()
    //     });

    //     return redirect()->route('admin.priceOffer.index')
    //         ->with('success', 'Paket berhasil dibuat.');
    // }

    // public function edit(PriceOffer $priceOffer)
    // {
    //     $priceOffer->load('items.menu');
    //     $menus = Menu::active()->orderBy('category')->orderBy('name')->get()->groupBy('category');
    //     return view('admin.priceOffer.form', compact('priceOffer', 'menus'));
    // }

    // public function update(Request $request, PriceOffer $priceOffer)
    // {
    //     $request->validate([
    //         'name'          => 'required|string|max:255',
    //         'package_price' => 'required|numeric|min:0',
    //         'items'         => 'required|array|min:1',
    //         'items.*.menu_id'  => 'required|exists:menus,id',
    //         'items.*.quantity' => 'required|integer|min:1',
    //     ]);

    //     DB::transaction(function () use ($request, $priceOffer) {
    //         $priceOffer->update($request->except(['items', 'image', '_token', '_method']));

    //         // Hapus items lama, replace dengan yang baru
    //         $priceOffer->items()->delete();
    //         foreach ($request->items as $i => $item) {
    //             $priceOffer->items()->create([
    //                 'menu_id'    => $item['menu_id'],
    //                 'quantity'   => $item['quantity'],
    //                 'category'   => $item['category'] ?? null,
    //                 'sort_order' => $item['sort_order'] ?? $i,
    //             ]);
    //         }
    //     });

    //     return redirect()->route('admin.priceOffer.index')
    //         ->with('success', 'Paket berhasil diperbarui.');
    // }

    // public function destroy(PriceOffer $priceOffer)
    // {
    //     $priceOffer->delete();
    //     return back()->with('success', 'Paket dihapus.');
    // }

    // public function toggleActive(PriceOffer $priceOffer)
    // {
    //     $priceOffer->update(['is_active' => ! $priceOffer->is_active]);
    //     return back()->with('success', 'Status paket diperbarui.');
    // }

    /**
     * Tampilkan daftar semua price offer.
     */
    public function index(Request $request)
    {
        $query = PriceOffer::withCount('items');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('category', 'like', "%{$request->search}%")
                    ->orWhere('badge', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $priceOffers = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = PriceOffer::where('deleted_at', null)->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $stats = [
            'total'    => PriceOffer::count(),
            'active'   => PriceOffer::where('is_active', true)->count(),
            'inactive' => PriceOffer::where('is_active', false)->count(),
            'trashed'  => PriceOffer::onlyTrashed()->count(),
        ];

        return view('admin.priceOffer.index', compact('priceOffers', 'categories', 'stats'));
    }

    /**
     * Tampilkan form buat paket baru.
     */
    public function create()
    {
        $menus = $this->getMenusGrouped();

        return view('admin.priceOffer.create', compact('menus'));
    }

    /**
     * Simpan paket baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'badge'             => 'nullable|string|max:50',
            'package_price'     => 'required|numeric|min:0',
            'is_active'         => 'boolean',
            'sort_order'        => 'integer|min:0',
            'category'          => 'nullable|string|max:100',
            'available_from'    => 'nullable|date_format:H:i',
            'available_until'   => 'nullable|date_format:H:i|after:available_from',
            'items'             => 'required|array|min:1',
            'items.*.menu_id'   => 'required|exists:menus,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ], [
            'items.required'           => 'Paket harus memiliki minimal 1 menu item.',
            'items.min'                => 'Paket harus memiliki minimal 1 menu item.',
            'items.*.menu_id.required' => 'Setiap baris item harus memilih menu.',
        ]);

        try {
            DB::beginTransaction();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('priceOffer', 'public');
            }

            // Load menu + relasi category sekaligus untuk hitung original_price
            $menuIds       = collect($validated['items'])->pluck('menu_id');
            $menus         = Menu::with('category')->whereIn('id', $menuIds)->get()->keyBy('id');
            $originalPrice = 0;

            foreach ($validated['items'] as $item) {
                $menu = $menus->get($item['menu_id']);
                if ($menu) {
                    $originalPrice += $menu->price * $item['quantity'];
                }
            }

            $priceOffer = PriceOffer::create([
                'name'            => $validated['name'],
                'description'     => $validated['description'] ?? null,
                'image'           => $imagePath,
                'badge'           => $validated['badge'] ?? null,
                'package_price'   => $validated['package_price'],
                'original_price'  => $originalPrice,
                'is_active'       => $request->boolean('is_active', true),
                'sort_order'      => $validated['sort_order'] ?? 0,
                'category'        => $validated['category'] ?? null,
                'available_from'  => $validated['available_from'] ?? null,
                'available_until' => $validated['available_until'] ?? null,
            ]);

            foreach ($validated['items'] as $index => $item) {
                $menu = $menus->get($item['menu_id']);
                if (!$menu) continue;

                PriceOfferItem::create([
                    'price_offer_id' => $priceOffer->id,
                    'menu_id'        => $item['menu_id'],
                    'item_name'      => $menu->name,
                    'item_price'     => $menu->price,
                    // Snapshot nama kategori dari relasi, bukan dari kolom langsung
                    'category'       => $menu->category?->name ?? null,
                    'quantity'       => $item['quantity'],
                    'sort_order'     => $index,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('price-offers.index')
                ->with('success', "Paket \"{$priceOffer->name}\" berhasil dibuat.");
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail satu paket.
     */
    public function show(PriceOffer $priceOffer)
    {
        $priceOffer->load('items');

        return view('admin.priceOffer.show', compact('priceOffer'));
    }

    /**
     * Tampilkan form edit paket.
     */
    public function edit(PriceOffer $priceOffer)
    {
        $priceOffer->load('items');

        $menus = $this->getMenusGrouped();

        return view('admin.priceOffer.edit', compact('priceOffer', 'menus'));
    }

    /**
     * Perbarui data paket.
     */
    public function update(Request $request, PriceOffer $priceOffer)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'badge'             => 'nullable|string|max:50',
            'package_price'     => 'required|numeric|min:0',
            'is_active'         => 'boolean',
            'sort_order'        => 'integer|min:0',
            'category'          => 'nullable|string|max:100',
            'available_from'    => 'nullable|date_format:H:i',
            'available_until'   => 'nullable|date_format:H:i|after:available_from',
            'items'             => 'required|array|min:1',
            'items.*.menu_id'   => 'required|exists:menus,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $imagePath = $priceOffer->image;

            if ($request->hasFile('image')) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('priceOffer', 'public');
            }

            if ($request->boolean('remove_image') && $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $imagePath = null;
            }

            $menuIds       = collect($validated['items'])->pluck('menu_id');
            $menus         = Menu::with('category')->whereIn('id', $menuIds)->get()->keyBy('id');
            $originalPrice = 0;

            foreach ($validated['items'] as $item) {
                $menu = $menus->get($item['menu_id']);
                if ($menu) {
                    $originalPrice += $menu->price * $item['quantity'];
                }
            }

            $priceOffer->update([
                'name'            => $validated['name'],
                'description'     => $validated['description'] ?? null,
                'image'           => $imagePath,
                'badge'           => $validated['badge'] ?? null,
                'package_price'   => $validated['package_price'],
                'original_price'  => $originalPrice,
                'is_active'       => $request->boolean('is_active', true),
                'sort_order'      => $validated['sort_order'] ?? 0,
                'category'        => $validated['category'] ?? null,
                'available_from'  => $validated['available_from'] ?? null,
                'available_until' => $validated['available_until'] ?? null,
            ]);

            // Ganti semua items dengan data baru
            $priceOffer->items()->delete();

            foreach ($validated['items'] as $index => $item) {
                $menu = $menus->get($item['menu_id']);
                if (!$menu) continue;

                PriceOfferItem::create([
                    'price_offer_id' => $priceOffer->id,
                    'menu_id'        => $item['menu_id'],
                    'item_name'      => $menu->name,
                    'item_price'     => $menu->price,
                    'category'       => $menu->category?->name ?? null,
                    'quantity'       => $item['quantity'],
                    'sort_order'     => $index,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('price-offers.index')
                ->with('success', "Paket \"{$priceOffer->name}\" berhasil diperbarui.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Soft-delete paket.
     */
    public function destroy(PriceOffer $priceOffer)
    {
        $name = $priceOffer->name;
        $priceOffer->delete();

        return back()->with('success', "Paket \"{$name}\" telah dihapus.");
    }

    /**
     * Pulihkan paket yang di-soft-delete.
     */
    public function restore(int $id)
    {
        $priceOffer = PriceOffer::onlyTrashed()->findOrFail($id);
        $priceOffer->restore();

        return back()->with('success', "Paket \"{$priceOffer->name}\" berhasil dipulihkan.");
    }

    /**
     * Hapus permanen.
     */
    public function forceDelete(int $id)
    {
        $priceOffer = PriceOffer::onlyTrashed()->findOrFail($id);

        if ($priceOffer->image) {
            Storage::disk('public')->delete($priceOffer->image);
        }

        $priceOffer->forceDelete();

        return back()->with('success', 'Paket telah dihapus secara permanen.');
    }

    /**
     * Toggle aktif/nonaktif via AJAX.
     */
    public function toggleStatus(PriceOffer $priceOffer)
    {
        $priceOffer->update(['is_active' => !$priceOffer->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $priceOffer->is_active,
            'message'   => $priceOffer->is_active
                ? "Paket \"{$priceOffer->name}\" diaktifkan."
                : "Paket \"{$priceOffer->name}\" dinonaktifkan.",
        ]);
    }

    /**
     * Update urutan tampilan via drag-and-drop (AJAX).
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:price_offers,id',
        ]);

        foreach ($request->order as $position => $id) {
            PriceOffer::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Ambil semua menu aktif dengan eager-load relasi category,
     * lalu group berdasarkan nama kategori.
     *
     * Hasilnya: Collection< string(nama_kategori), Collection<Menu> >
     * Siap dipakai di optgroup pada form select.
     */
    private function getMenusGrouped()
    {
        return Menu::with('category')
            ->where('is_available', true)
            ->orderBy('name')
            ->get()
            ->groupBy(fn(Menu $menu) => $menu->category?->name ?? 'Tanpa Kategori')
            ->sortKeys();
    }



    // ============================================================
    // API — Dipakai oleh QR self-order menu (return JSON)
    // ============================================================

    /**
     * Endpoint untuk QR menu: ambil semua paket yang sedang aktif & tersedia.
     * GET /api/packages
     */
    public function apiIndex()
    {
        $packages = PriceOffer::active()
            ->availableNow()
            ->with('items.menu')
            ->orderBy('sort_order')
            ->get()
            ->map(fn($p) => [
                'id'               => $p->id,
                'name'             => $p->name,
                'description'      => $p->description,
                'badge'            => $p->badge,
                'image'            => $p->image ? asset('storage/' . $p->image) : null,
                'package_price'    => $p->package_price,
                'original_price'   => $p->original_price,
                'savings'          => $p->savings,
                'savings_percent'  => $p->savings_percent,
                'category'         => $p->category,
                'items'            => $p->items->map(fn($item) => [
                    'name'     => $item->item_name,
                    'category' => $item->category,
                    'quantity' => $item->quantity,
                    'price'    => $item->item_price,
                ]),
            ]);

        return response()->json($packages);
    }
}

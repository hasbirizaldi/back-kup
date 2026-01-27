<?php

namespace App\Http\Controllers;

use App\Models\Promosi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromosiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Promosi::orderBy('featured', 'desc')->latest()->paginate(6)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'featured' => 'nullable|boolean'
        ]);

        $imagePath = $request->file('image')->store('promosi', 'public');

        $promosi = Promosi::create([
            'title' => $request->title,
            'image' => $imagePath,
            'featured' => $request->featured ?? false
        ]);

        return response()->json([
            'success' => true,
            'data' => $promosi
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $promosi = Promosi::findOrFail($id);

        $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'featured' => 'nullable|boolean'
        ]);

        // Jika upload gambar baru
        if ($request->hasFile('image')) {
            // hapus gambar lama
            if ($promosi->image && Storage::disk('public')->exists($promosi->image)) {
                Storage::disk('public')->delete($promosi->image);
            }

            $imagePath = $request->file('image')->store('promosi', 'public');
            $promosi->image = $imagePath;
        }

        $promosi->title = $request->title;
        $promosi->featured = $request->featured ?? false;
        $promosi->save();

        return response()->json([
            'success' => true,
            'data'    => $promosi,
            'message' => 'Promosi berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $promosi = Promosi::findOrFail($id);

        Storage::disk('public')->delete($promosi->image);
        $promosi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Promosi berhasil dihapus'
        ]);
    }

    // =============================ROUTE PUBLIC===========================

    public function publicPromosi(){
         return response()->json([
            'success' => true,
            'data' => Promosi::orderBy('featured', 'desc')->latest()->paginate(6)
        ]);
    }
}

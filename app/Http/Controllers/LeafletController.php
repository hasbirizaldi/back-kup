<?php

namespace App\Http\Controllers;

use App\Models\Leaflet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeafletController extends Controller
{
     public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Leaflet::orderBy('featured', 'desc')->latest()->paginate(6)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:1048',
            'featured' => 'nullable|in:0,1'
        ]);

        $imagePath = $request->file('image')->store('leaflet', 'public');

        $leaflet = Leaflet::create([
            'title' => $request->title,
            'image' => $imagePath,
            'featured' => $request->featured ?? false
        ]);

        return response()->json([
            'success' => true,
            'data' => $leaflet
        ], 201);
    }


   public function update(Request $request, $id)
    {
        $leaflet = Leaflet::findOrFail($id);

        $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:1048',
            'featured' => 'nullable|in:0,1'
        ]);

        if ($request->hasFile('image')) {
            if ($leaflet->image && Storage::disk('public')->exists($leaflet->image)) {
                Storage::disk('public')->delete($leaflet->image);
            }

            $leaflet->image = $request->file('image')->store('leaflet', 'public');
        }

        $leaflet->title = $request->title;
        $leaflet->featured = $request->featured ?? 0;
        $leaflet->save();

        return response()->json([
            'success' => true,
            'data' => $leaflet
        ]);
}


    public function destroy($id)
    {
        $leaflet = Leaflet::findOrFail($id);

        Storage::disk('public')->delete($leaflet->image);
        $leaflet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leaflet berhasil dihapus'
        ]);
    }

    // =================PUBLIC Leaflet================================
    public function publicLeaflet()
    {
        return response()->json([
            'success' => true,
            'data' => Leaflet::orderBy('featured', 'desc')->latest()->paginate(6)
        ]);
    }
}

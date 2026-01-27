<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
     public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Gallery::orderBy('featured', 'desc')->latest()->paginate(6)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:1048',
            'featured' => 'nullable|in:0,1'
        ]);


        $imagePath = $request->file('image')->store('gallery', 'public');

        $gallery = Gallery::create([
            'title' => $request->title,
            'image' => $imagePath,
            'featured' => $request->featured ?? false
        ]);

        return response()->json([
            'success' => true,
            'data' => $gallery
        ], 201);
    }


   public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:1048',
            'featured' => 'nullable|in:0,1'
        ]);

        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }

            $gallery->image = $request->file('image')->store('gallery', 'public');
        }

        $gallery->title = $request->title;
        $gallery->featured = $request->featured ?? 0;
        $gallery->save();

        return response()->json([
            'success' => true,
            'data' => $gallery
        ]);
}


    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gallery berhasil dihapus'
        ]);
    }

    // =================PUBLIC GALLERY================================
    public function publicGallery()
    {
        return response()->json([
            'success' => true,
            'data' => Gallery::orderBy('featured', 'desc')->latest()->paginate(6)
        ]);
    }
}

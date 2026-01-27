<?php

namespace App\Http\Controllers;

use App\Models\Vidio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VidioController extends Controller
{
    /* ================= INDEX (ADMIN) ================= */
    public function index(Request $request)
    {
        $query = Vidio::query();

        // 🔍 SEARCH BY TITLE
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $vidios = $query
            ->latest()
            ->paginate(6);

        return response()->json([
            'success' => true,
            'data' => $vidios
        ]);
    }


    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'link'      => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured'  => 'nullable|boolean',
            'status'    => 'nullable|boolean',
        ]);

        // ================= SLUG AMAN (ANTI DUPLIKAT) =================
        $slug = Str::slug($request->title);
        $count = Vidio::where('slug', 'like', "$slug%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        // ================= UPLOAD THUMBNAIL =================
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('vidios', 'public');
        }

        if (!$thumbnailPath && str_contains($request->link, 'youtube')) {
            preg_match('/v=([^&]+)/', $request->link, $matches);
            if (isset($matches[1])) {
                $thumbnailPath = 'https://img.youtube.com/vi/'.$matches[1].'/hqdefault.jpg';
            }
        }

        // ================= SIMPAN DATA =================
        $vidio = Vidio::create([
            'title'     => $request->title,
            'slug'      => $slug,
            'category'  => $request->category,
            'link'      => $request->link,
            'thumbnail' => $thumbnailPath,
            'featured'  => $request->boolean('featured'),
            'status'    => $request->boolean('status', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vidio berhasil ditambahkan',
            'data'    => $vidio
        ], 201);
    }


    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        $vidio = Vidio::findOrFail($id);

        $request->validate([
            'title'     => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'link'      => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured'  => 'boolean',
            'status'    => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($vidio->thumbnail) {
                Storage::disk('public')->delete($vidio->thumbnail);
            }
            $vidio->thumbnail = $request->file('thumbnail')->store('vidios', 'public');
        }

        $vidio->update([
            'title'     => $request->title,
            'slug'      => Str::slug($request->title),
            'category'  => $request->category,
            'link'      => $request->link,
            'featured'  => $request->featured ?? $vidio->featured,
            'status'    => $request->status ?? $vidio->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $vidio
        ]);
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $vidio = Vidio::findOrFail($id);

        if ($vidio->thumbnail) {
            Storage::disk('public')->delete($vidio->thumbnail);
        }

        $vidio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil dihapus'
        ]);
    }

    /* ================= PUBLIC FEATURED (LOAD MORE) ================= */
    public function featured(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        $category = $request->get('category'); // 👈 ambil category

        $query = Vidio::select(
            'id',
            'title',
            'slug',
            'category',
            'link',
            'thumbnail',
            'created_at'
        )
        ->where('featured', 1)
        ->where('status', 1);

        // ✅ filter category kalau ada
        if ($category) {
            $query->where('category', $category);
        }

        $vidios = $query->latest()->simplePaginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $vidios->items(),
            'meta' => [
                'current_page' => $vidios->currentPage(),
                'has_more' => $vidios->hasMorePages(),
                'next_page' => $vidios->currentPage() + 1
            ]
        ]);
    }


}

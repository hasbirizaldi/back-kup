<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Artikel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::with(['user:id,name', 'tags:id,name,slug'])->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('featured')) {
            $query->where('featured', 1);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10);

        return response()->json([
            'success' => true,
            'total'   => $data->total(),   // 👈 jumlah seluruh data
            'data'    => $data
        ]);
    }

    /**
     * GET /artikel/{slug}
     * Detail artikel
     */
    public function show($slug)
    {
        $artikel = Artikel::with(['user:id,name', 'tags:id,name,slug'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($artikel->noindex) {
            abort(404);
        }

        return response()->json([
            'success' => true,
            'data' => $artikel
        ]);
    }

    /**
     * POST /artikels
     * Create artikel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:artikels,slug',
            'excerpt' => 'nullable|string',
            'published_at' => 'nullable|date',

            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',

            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',

            'content' => 'required|string',
            
            'status' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'noindex' => 'nullable|boolean',

            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',

        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('artikels', 'public');
        }


        $validated['user_id'] = auth()->id();

        // Set slug jika belum ada
        if (empty($validated['slug'])) {
            $slug = Str::slug($validated['title']);
            $count = Artikel::where('slug', 'like', "$slug%")->count();
            $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;
        }


        // Set default boolean jika null
        $validated['status']   = $validated['status']   ?? 1;
        $validated['featured'] = $validated['featured'] ?? 0;
        $validated['noindex']  = $validated['noindex']  ?? 0;
        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $artikel = Artikel::create($validated);
        // ================= TAGS =================
        if (!empty($tags)) {
            $tagIds = [];

            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );

                $tagIds[] = $tag->id;
            }

            $artikel->tags()->sync($tagIds);
        }


        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dibuat',
            'data' => $artikel
        ], 201);
    }


    /**
     * PUT /artikels/{id}
     * Update artikel
     */
    public function update(Request $request, $slug)
{
    $artikel = Artikel::where('slug', $slug)->firstOrFail();

    $validated = $request->validate([
        'category' => 'required|in:Kesehatan,Islami',
        'title' => 'required|string|max:255',
        'excerpt' => 'nullable|string',
        'published_at' => 'nullable|date',

        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'image_alt' => 'nullable|string|max:255',

        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'canonical_url' => 'nullable|url',

        'content' => 'required|string',

        'status' => 'nullable|boolean',
        'featured' => 'nullable|boolean',
        'noindex' => 'nullable|boolean',

        'tags' => 'nullable|array',
        'tags.*' => 'string|max:50',
    ]);

    // 🔥 AMBIL TAGS & HAPUS DARI VALIDATED
    $tags = $validated['tags'] ?? [];
    unset($validated['tags']);

    // ================= SLUG =================
    if ($artikel->title !== $validated['title']) {
        $newSlug = Str::slug($validated['title']);

        $count = Artikel::where('slug', 'like', "$newSlug%")
            ->where('id', '!=', $artikel->id)
            ->count();

        $validated['slug'] = $count
            ? "{$newSlug}-" . ($count + 1)
            : $newSlug;
    }

    // ================= IMAGE =================
    if ($request->hasFile('image')) {
        if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
            Storage::disk('public')->delete($artikel->image);
        }

        $validated['image'] = $request->file('image')->store('artikels', 'public');
    }

    // ================= BOOLEAN =================
    $validated['status']   = $validated['status']   ?? 0;
    $validated['featured'] = $validated['featured'] ?? 0;
    $validated['noindex']  = $validated['noindex']  ?? 0;

    // 🔥 UPDATE ARTIKEL (TANPA TAGS)
    $artikel->update($validated);

    // ================= TAGS =================
    $tagIds = [];
    foreach ($tags as $tagName) {
        $tag = Tag::firstOrCreate(
            ['slug' => Str::slug($tagName)],
            ['name' => $tagName]
        );
        $tagIds[] = $tag->id;
    }

    $artikel->tags()->sync($tagIds);

    return response()->json([
        'success' => true,
        'message' => 'Artikel berhasil diperbarui',
        'data' => $artikel->load('tags')
    ]);
}


    /**
     * DELETE /artikels/{id}
     * Delete artikel
     */
    public function destroy($slug)
    {
        $artikel = Artikel::where('slug', $slug)->firstOrFail();

        if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
            Storage::disk('public')->delete($artikel->image);
        }

        $artikel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus'
        ]);
    }

    // =========================Public Artikel==============================
    public function home_artikel(Request $request)
    {
        $query = Artikel::with(['user:id,name', 'tags:id,name,slug'])
            ->where('status', 1)
            ->where('noindex', 0);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }


        $artikels = $query
        ->orderByDesc('published_at')
            ->paginate(6); // ⬅️ PENTING

        return response()->json([
            'success' => true,
            'data' => $artikels
        ]);
    }

    public function detail_artikel($slug)
    {
       $artikel = Artikel::with(['user:id,name', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->where('noindex', 0)
            ->first();

        if (!$artikel) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        $related = Artikel::where('category', $artikel->category)
            ->where('id', '!=', $artikel->id)
            ->where('status', 1)
            ->where('featured', 1)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $artikel,
            'related' => $related
        ]);
    }

    public function artikel_by_tag($slug)
    {
        $tag = Tag::where('slug', $slug)->first();

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag tidak ditemukan'
            ], 404);
        }

        $artikels = $tag->artikels()->where('status', 1)->paginate(6);

        return response()->json([
            'success' => true,
            'tag' => $tag,
            'data' => $artikels
        ]);
    }


    public function artikelFeatured()
    {
        $artikels = Artikel::select(
                'id',
                'category',
                'title',
                'slug',
                'excerpt',
                'image',
                'image_alt',
                'published_at',
                'reading_time'
            )
            ->where('featured', 1)
            ->where('status', 1)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $artikels
        ]);
    }

    public function artikelNews()
    {
        $artikels = Artikel::select(
                'id',
                'category',
                'title',
                'slug',
                'excerpt',
                'image',
                'image_alt',
                'published_at',
                'reading_time'
            )
            ->where('status', 1)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $artikels
        ]);
    }





}

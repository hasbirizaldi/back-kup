<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artikel extends Model
{
     use HasFactory;

    protected $table = 'artikels';

    /**
     * Mass assignment
     */
    protected $fillable = [
        'user_id',
        'category',
        'title',
        'slug',
        'excerpt',
        'published_at',
        'image',
        'image_alt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'content',
        'reading_time',
        'noindex',
        'status',
        'featured',
    ];

    /**
     * Cast data type
     */
    protected $casts = [
        'published_at' => 'date',
        'status' => 'boolean',
        'featured' => 'boolean',
        'noindex' => 'boolean',
    ];

    /**
     * Relasi: Artikel → User (Author)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Auto generate slug & reading time
     */
    protected static function booted()
    {
        static::creating(function ($artikel) {

            // Slug otomatis
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->title);
            }

            // Excerpt otomatis jika kosong
            if (empty($artikel->excerpt)) {
                $artikel->excerpt = Str::limit(strip_tags($artikel->content), 160);
            }

            // Reading time (200 kata / menit)
            if (empty($artikel->reading_time)) {
                $wordCount = str_word_count(strip_tags($artikel->content));
                $artikel->reading_time = ceil($wordCount / 200);
            }
        });
    }

    /**
     * Scope: artikel published
     */
    public function scopePublished($query)
    {
        return $query->where('status', 1)
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    /**
     * Scope: featured artikel
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', 1);
    }

     public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'artikel_tag',      // pivot table
            'artikel_id',       // FK di pivot
            'tag_id'            // FK di pivot
        );
    }
}

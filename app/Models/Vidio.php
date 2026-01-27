<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vidio extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'category',
        'link',
        'thumbnail',
        'featured',
        'status'
    ];


    /* AUTO SLUG */
    protected static function booted()
    {
        static::creating(function ($vidio) {
            if (empty($vidio->slug)) {
                $vidio->slug = Str::slug($vidio->title);
            }
        });
    }
}

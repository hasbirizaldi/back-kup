<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;
    protected $fillable = [
    'title',
    'description',
    'requirements',
    'documents',
    'deadline',
    'is_active'
];

protected $casts = [
    'requirements' => 'array',
    'documents' => 'array',
    'is_active' => 'boolean',
];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'publication_date'
    ];

    protected $casts = [
        'publication_date' => 'date'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}

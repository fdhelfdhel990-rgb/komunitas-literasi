<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author_id',
        'publisher_id',
        'genre_id',
        'description',
        'isbn',
        'cover_path',
        'is_published',
        'created_by'
    ];

}

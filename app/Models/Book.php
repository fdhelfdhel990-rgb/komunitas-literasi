<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = ['id'];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }
    
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

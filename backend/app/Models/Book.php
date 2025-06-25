<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Book extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'google_id',
        'title',
        'author',
        'description',
        'cover_url',
        'genre',
        'published_date',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    // Un livre peut être liké ou lu par plusieurs utilisateurs
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_books')
                    ->withPivot('liked', 'read')
                    ->withTimestamps();
    }
}

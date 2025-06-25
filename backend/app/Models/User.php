<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id'];

    // Un utilisateur peut aimer plusieurs livres
    public function books()
    {
        return $this->belongsToMany(Book::class, 'user_books')
                    ->withPivot('liked', 'read')
                    ->withTimestamps();
    }
}

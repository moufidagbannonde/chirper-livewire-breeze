<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chirp extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
    ];

    // connecter un utilisateur à ses chirps 
    // un chirp ne peut venir que d'un seul utilisateur

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

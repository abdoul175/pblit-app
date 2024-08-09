<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envoie extends Model
{
    use HasFactory;

    protected $fillable = [
        'compte',
        'nom',
        'montant',
        'police'
    ];
}

<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variety extends Model {
    use HasFactory;

    protected $fillable = ['slug', 'name'];

    public function pokemons() {
        return $this->belongsToMany( Pokemon::class, 'pokemons_varieties' );
    }
}

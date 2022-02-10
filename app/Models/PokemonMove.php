<?php

namespace App\Models;

use App\Models\Move;
use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PokemonMove extends Pivot {
    public $table = "pokemons_moves";

    protected $fillable = ['pokemon_id', 'move_id'];

    public function move() {
        return $this->belongsTo( Move::class, 'move_id', 'id' );
    }

    public function pokemon() {
        return $this->belongsTo( Pokemon::class, 'pokemon_id', 'id' );
    }
}

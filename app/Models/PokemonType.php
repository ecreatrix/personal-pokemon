<?php

namespace App\Models;

use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PokemonType extends Pivot {
    public $table = "pokemons_types";

    protected $fillable = ['pokemon_id', 'type_id'];

    public function pokemon() {
        return $this->belongsTo( Pokemon::class, 'pokemon_id', 'id' );
    }

    public function type() {
        return $this->belongsTo( Type::class, 'type_id', 'id' );
    }
}

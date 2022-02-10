<?php

namespace App\Models;

use App\Models\Ability;
use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PokemonAbility extends Pivot {
    public $table = "pokemons_abilities";

    protected $fillable = ['pokemon_id', 'ability_id'];

    public function ability() {
        return $this->belongsTo( Ability::class, 'ability_id', 'id' );
    }

    public function pokemon() {
        return $this->belongsTo( Pokemon::class, 'pokemon_id', 'id' );
    }
}

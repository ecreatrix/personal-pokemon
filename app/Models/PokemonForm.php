<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PokemonForm extends Pivot {
    public $table = "pokemons_forms";

    protected $fillable = ['pokemon_id', 'form_id'];

    public function form() {
        return $this->belongsTo( Form::class, 'form_id', 'id' );
    }

    public function pokemon() {
        return $this->belongsTo( Pokemon::class, 'pokemon_id', 'id' );
    }
}

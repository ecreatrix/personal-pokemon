<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PokemonCard extends Pivot {
	public $table = "pokemons_cards";

	protected $fillable = ['pokemon_id', 'card_id'];

	public function card() {
		return $this->belongsTo( Card::class, 'card_id', 'id' );
	}

	public function pokemon() {
		return $this->belongsTo( Pokemon::class, 'pokemon_id', 'id' );
	}
}

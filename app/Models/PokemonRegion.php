<?php

namespace App\Models;

use App\Models\Pokemon;
use App\Models\Region;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PokemonRegion extends Pivot {
	public $table = "pokemons_regions";

	protected $fillable = ['pokemon_id', 'region_id'];

	public function pokemon() {
		return $this->belongsTo( Pokemon::class, 'pokemon_id', 'id' );
	}

	public function region() {
		return $this->belongsTo( Region::class, 'region_id', 'id' );
	}
}

<?php

namespace App\Models;

use App\Models\Region;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model {
	use HasFactory;

	public $table = "pokemons";

	protected $fillable = ['slug', 'pokedex_no'];

	/**
	 * The cards that belong to the pokemon
	 */
	public function cards() {
		return $this->belongsToMany( Card::class, 'pokemons_cards', 'pokemon_id', 'card_id' );
	}

	/**
	 * Get the first region it was found in
	 */
	public function region() {
		return $this->belongsTo( Region::class, 'region_id', 'id' );
	}

	public function types() {
		return $this->belongsToMany( Type::class, 'types', 'id', 'id' );
	}
}

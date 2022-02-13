<?php

namespace App\Models;

use App\Models\Ability;
use App\Models\Card;
use App\Models\Move;
use App\Models\Region;
use App\Models\Type;
use App\Services\Naming;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pokemon extends Model {
	use HasFactory;

	public $table = "pokemons";

	protected $fillable = ['slug', 'pokedex_no'];

	public function abilities() {
		//return $this->belongsToMany( Ability::class, 'pokemons_abilities' );
	}

	public function cards() {
		//return $this->belongsToMany( Card::class, 'pokemons_cards', 'pokemon_id', 'card_id' );
	}

	public function moves() {
		//return $this->belongsToMany( Move::class, 'pokemons_moves' );
	}

	public function regions() {
		//return $this->belongsToMany( Region::class, 'pokemons_regions' );
	}

	public function types() {
		return $this->belongsToMany( Type::class, 'pokemons_types' );
	}

	public function typesCached() {
		// we have to give it a unique name cuz this will be tied
		// to the instance that calls it
		//Cache::forget( Naming::cacheKey( $this, 'types' ) );
		//$value = $this->belongsToMany( Type::class, 'pokemons_types' );
		//\Debugbar::info( $value );

		return Cache::rememberForever( Naming::cacheKey( $this, 'types' ), function () {
			return $this->types()->get();
		} );
	}
}

<?php

namespace App\Models;

use App\Models\Ability;
use App\Models\Card;
use App\Models\Move;
use App\Models\Region;
use App\Models\Type;
use App\Models\Variety;
use App\Services\Naming;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pokemon extends Model {
	use HasFactory;

	public $table = "pokemons";

	protected $fillable = ['slug', 'pokedex_no'];

	private static $main_pokemon_columns = ['pokemons.id', 'pokedex_no', 'name', 'slug', 'colour', 'image_slug', 'text'];

	public function abilities() {
		//return $this->belongsToMany( Ability::class, 'pokemons_abilities' );
	}

	public function cards() {
		//return $this->belongsToMany( Card::class, 'pokemons_cards', 'pokemon_id', 'card_id' );
	}

	public function moves() {
		//return $this->belongsToMany( Move::class, 'pokemons_moves' );
	}

	public function next_stage() {
		$next_stage_no = $this->next_stage;

		if ( $next_stage_no ) {
			return Pokemon::firstWhere( 'pokedex_no', $next_stage_no );
		}

		return false;
	}

	public function previous_stage() {
		$previous_stage_no = $this->previous_stage;

		if ( $previous_stage_no ) {
			return Pokemon::firstWhere( 'pokedex_no', $previous_stage_no );
		}

		return false;
	}

	public static function ranged( $start, $end ) {
		return Pokemon::where( 'pokedex_no', '>=', $start )->select( $this->main_pokemon_columns )->where( 'pokedex_no', '<=', $end )->select( self::$main_pokemon_columns )->with( 'types' )->with( 'varieties' );
	}

	public static function rangedCached( $start, $end ) {
		$cache_key = 'numbers_pokemons_' . $start . '_' . $end;
		$cache     = Cache::get( $cache_key );
		Cache::forget( $cache_key );

		if ( false && $cache ) {
			return $cache;
		} else {
			//return Cache::rememberForever( $cache_key, function () use ( $start, $end ) {
			return self::ranged( $start, $end )->get()->toArray();
			//} );
		}
	}

	public function regions() {
		return $this->belongsToMany( Region::class, 'pokemons_regions' );
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
			return $this->types()->get()->toArray();
		} );
	}
}

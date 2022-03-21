<?php

namespace App\Models;

use App\Models\Pokemon;
use App\Services\Naming;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Region extends Model {
	use HasFactory;

	public $table = "regions";

	protected $fillable = ['name', 'slug', 'number'];

	private $main_pokemon_columns = ['pokemons.id', 'pokemons.variety_id', 'pokemons_regions.primary', 'pokedex_no', 'name', 'slug', 'colour', 'image_slug', 'text_y', 'text_x', 'api_text'];

	public function pokemons() {
		return $this->belongsToMany( Pokemon::class, 'pokemons_regions' )->select( $this->main_pokemon_columns )->with( 'types' );
	}

	public function pokemonsByVariety( $varieties = [1] ) {
		$pokemons = $this->belongsToMany( Pokemon::class, 'pokemons_regions' )->where( 'pokemons_regions.primary', 1 )->select( $this->main_pokemon_columns )->with( 'types' );

		if ( $varieties ) {
			$pokemons = $pokemons->whereIn( 'pokemons.variety_id', $varieties );
		}

		return $pokemons;
	}

	public function pokemonsCached() {
		$key = Naming::cacheKey( $this, 'pokemons_regions' );
		//cache::forget( $key );

		return Cache::rememberForever( $key, function () {
			return $this->pokemons()->get()->toArray();
		} );
	}

	public function pokemonsRanged( $start, $end ) {
		return $this->belongsToMany( Pokemon::class, 'pokemons_regions' )->where( 'pokedex_no', '>=', $start )->where( 'pokedex_no', '<=', $end )->select( $this->main_pokemon_columns )->with( 'types' );
	}

	public function pokemonsRangedCached( $start, $end ) {
		$key = Naming::cacheKey( $this, 'pokemons_regions_ranged_' . $start . '_' . $end );
		//cache::forget( $key );

		return Cache::rememberForever( $key, function () use ( $start, $end ) {
			return $this->pokemonsRanged( $start, $end )->get()->toArray();
		} );
	}

	//->where( 'pokemons.variety_id', 1 )
	public function primaryPokemons() {
		return $this->belongsToMany( Pokemon::class, 'pokemons_regions' )->where( 'pokemons.variety_id', 1 )->where( 'pokemons_regions.primary', 1 )->select( $this->main_pokemon_columns )->with( 'types' );
	}

	public function primaryPokemonsCached() {
		$key = Naming::cacheKey( $this, 'primary_pokemons_regions' );
		//cache::forget( $key );

		return Cache::rememberForever( $key, function () {
			return $this->primaryPokemons()->get()->toArray();
		} );
	}

	public function primaryPokemonsRanged( $start, $end ) {
		return $this->belongsToMany( Pokemon::class, 'pokemons_regions' )->where( 'pokedex_no', '>=', $start )->where( 'pokedex_no', '<=', $end )->select( $this->main_pokemon_columns )->with( 'types' );
	}

	public function primaryPokemonsRangedCached( $start, $end ) {
		$key = Naming::cacheKey( $this, 'primary_pokemons_regions_ranged_' . $start . '_' . $end );
		//cache::forget( $key );

		return Cache::rememberForever( $key, function () use ( $start, $end ) {
			return $this->primaryPokemonsRanged( $start, $end )->get()->toArray();
		} );
	}
}

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

	public function pokemons() {
		return $this->belongsToMany( Pokemon::class, 'pokemons_regions' );
	}

	public function pokemonsCached() {
		// we have to give it a unique name cuz this will be tied
		// to the instance that calls it
		//Cache::forget( Naming::cacheKey( $this, 'pokemons' ) );
		//$value = $this->belongsToMany( Type::class, 'pokemons_types' );
		//\Debugbar::info( $value );

		return Cache::rememberForever( Naming::cacheKey( $this, 'pokemons' ), function () {
			return $this->pokemons();
		} );
	}
}

<?php

namespace App\Http\Controllers;

use App\Services\Pokedex as PokedexService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PokedexManualUpdate extends BaseController {
    public function update() {
        $api      = ( new PokedexService() );
        $pokemons = [];

        for ( $pokedex_no = 850; $pokedex_no <= 898; $pokedex_no++ ) {
            $pokemons[] = $api->update_pokemon_from_id( $pokedex_no );
        }

        $return = [];
        array_walk_recursive( $pokemons, function ( $a ) use ( &$return ) {$return[] = $a;} );
        $pokemons = $return;

        //$api->create_region_custom();

        //$pokemons[] = $api->create_pokemon_custom( 3 );
        //$pokemons[] = $api->create_pokemon_custom( 422 );
        return view( 'pages.pokedex-updateAPI', compact( 'pokemons' ) );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Services\Pokedex as PokedexService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Pokedex extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index() {
        $pokemons = Pokemon::find( ['pokedex_no' => '001'] );

        //\Debugbar::info( $pokemons );
        return view( 'pages.pokedex', compact( 'pokemons' ) );
    }

    public function regions() {
        /*$api = ( new PokedexService() );

        $regions = [];
        for ( $i = 1; $i <= 1; $i++ ) {
        $api->region( $i, $json );
        }*/

        //\Debugbar::info( $regions );
    }

    public function update() {
        $api = ( new PokedexService() );

        //\Debugbar::startMeasure( 'pokemons' );

        $pokemons = [];
        for ( $i = 1; $i <= 898; $i++ ) {
            //\Debugbar::info( $i );
            //$pokemon = $api->single( $i );
            //\Debugbar::startMeasure( 'pokemons - ' . $i );
            $pokemons[] = $api->create_pokemon( $i );
            //\Debugbar::stopMeasure( 'pokemons - ' . $i );
        }

        //\Debugbar::info( $pokemons );
        //\Debugbar::stopMeasure( 'pokemons' );

        return view( 'pages.pokedex-update', compact( 'pokemons' ) );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Services\API;
use App\Services\Naming;
use App\Services\Pokedex as PokedexService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PokedexAPIUpdate extends BaseController {
    public function update() {
        $api      = ( new PokedexService() );
        $pokemons = [];

        for ( $pokedex_no = 750; $pokedex_no <= 900; $pokedex_no++ ) {
            $species = Api::poke_request( $pokedex_no, 'pokemon-species' );

            if ( $species ) {
                //clock( $species->varieties );
                foreach ( $species->varieties as $variety_info ) {
                    if ( $variety_info->is_default ) {
                        $url_id = false;
                    } else {
                        $url_id = Naming::url_id( $variety_info->pokemon->url, 'pokemon' );
                    }

                    //$pokemons[] = $api->aacreate_pokemon_custom( $species, $variety_info, $pokedex_no, $url_id );

                    $pokemons[] = $api->create_pokemon( $species, $variety_info, $pokedex_no, $url_id );
                }
            }
        }

        //$api->create_region_custom();

        //$pokemons[] = $api->create_pokemon_custom( 3 );
        //$pokemons[] = $api->create_pokemon_custom( 422 );
        return view( 'pages.pokedex-updateAPI', compact( 'pokemons' ) );
    }
}

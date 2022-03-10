<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class API {
    // Sections: pokemon, region, ...
    public static function poke_request( $name, $section = 'pokemon' ) {
        $query = $section . '/' . Str::slug( $name, '-' );

        $cache_key = 'pokeapi-' . $query;
        $cache     = Cache::get( $cache_key );
        //clock( 'https://pokeapi.co/api/v2/' . $query );

        if ( $cache && null != $cache ) {
            //\Debugbar::info( 'poke: ' . $cache_key . ' used' );
            $cache = json_decode( $cache );

            return $cache;
        } else {
            $url = 'https://pokeapi.co/api/v2/' . $query;

            $response = \Http::get( $url )->body();

            if ( 'Not Found' == $response ) {
                \Debugbar::info( 'Not found: ' . $url );
                \Debugbar::info( $response );
                return false;
            }

            Cache::put( $cache_key, $response, 500000 ); //500000 minutes

            $response = json_decode( $response );
            return $response;
        }
    }
}

<?php

namespace App\Services\TCG;

use App\Helpers\TextHelper;
use App\Helpers\TypeHelper;
use App\Models\Card;
use App\Models\Pokemon;
use App\Services\Pokemon;
use App\Services\TCGCard;
use App\Services\TCGDeck;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class API {
    public function __construct() {
    }

    public function body( $response ) {
        if ( ! $response || empty( $response ) ) {
            return false;
        }

        $body = json_decode( $response );
        //\Debugbar::info( $body );

        if ( ! $body || ! property_exists( $body, 'totalCount' ) || ! property_exists( $body, 'data' ) || 0 == $body->count ) {
            return false;
        }

        return $body->data;
    }

    public function get_cards( $page = 1, $query = '', $limit = 20 ) {
        //TEST/\Debugbar::startMeasure( 'TCG $this->tcg' );
        $tcg = false;
        $tcg = $this->tcg( $page, $query, $limit );

        //\Debugbar::stopMeasure( 'TCG $this->tcg' );
        ////TEST/\Debugbar::startMeasure( 'TCG $this->loop' );

        $items = 'No cards found';
        // If there are any cards available, loop through them to process
        if ( $tcg ) {
            //$num_pages = $cardsApi->count > 0 ? ceil( $cardsApi->totalCount / $cardsApi->count ) : 1;

            $items = $this->loop( $tcg );
        }

        //\Debugbar::stopMeasure( 'TCG $this->loop' );
        return $items;
    }

    public function loop( $data ) {
        $items = [];

        $card_slugs = [];

        //$pokemons = Pokemon::all();
        //$pokemons = Pokemon::findMany($ids)->with('orders')->get();
        //\Debugbar::info( $pokemons );

        // Sort out all find cards

        $pokemon_service = ( new Pokemon() );
        $card_service    = ( new TCGCard() );
        $deck_service    = ( new TCGDeck() );

        $all_cards    = [];
        $all_pokemons = [];
        //\Debugbar::startMeasure( "card_loop" );
        foreach ( $data as $card_info ) {
            $id        = $card_info->id;
            $image     = $card_info->images->large;
            $set       = $card_info->set;
            $name      = TextHelper::canadian_spelling( $card_info->name );
            $slug      = Str::slug( $name, '-' );
            $type      = Str::lower( $card_info->supertype );
            $cache_key = 'card_loop_' . $slug;
            $cache     = Cache::get( $cache_key );

            // To avoid n+1 database calls, use cache if available
            if ( false && $cache ) {
                $items[$slug . '-' . $id] = Cache::get( $cache_key );
            } else {
                $deck = $deck_service->db( $set );
                $this->download( $image, $set->id, $id, $slug );
                //$card = Card::firstOrNew( ['slug' => $slug] );

                //$custom = [];

                $pokemons = [];
                if ( TypeHelper::type_pokemon( $type ) ) {
                    // Add card DB link to pokemon DB
                    foreach ( $card_info->nationalPokedexNumbers as $pokedex_no ) {
                        //$pokemon_ids[$pokedex_no]  = $name;
                        //$all_pokemons[$pokedex_no] = $name;
                        $pokemon = $pokemon_service->db( false, $pokedex_no );
                        //$custom[] = $pokemon;
                        $pokemons[] = $pokemon;
                    }

                }

                $card = [
                    'card_info' => $card_info,
                    'deck'      => $deck,
                    'pokemons'  => $pokemons,
                ];

                ////TEST/\Debugbar::startMeasure( "cards_service" );
                $card        = $card_service->db( $card );
                $all_cards[] = $card;
                //$card = $card_service->db( $deck, $card_info, $id, $slug, $image, $custom );

                //$card->pokemons()->syncWithoutDetaching( $ids );
                //\Debugbar::stopMeasure( "cards_service" );

                //$items[$slug . '-' . $id] = $card;

                Cache::set( $cache_key, $card );
            }
        }
        //\Debugbar::stopMeasure( "card_loop" );
        \Debugbar::info( $all_cards );
        //\Debugbar::info( $all_pokemons );

        //\Debugbar::startMeasure( "card_model" );
        //$card_model = Card::whereIn( 'slug', array_keys( $all_cards ) )->get()->toArray();
        // foreach ( $all_cards as $card ) {
        //}
        //\Debugbar::stopMeasure( "card_model" );
        //\Debugbar::info( $card_model );

        //\Debugbar::startMeasure( "pokemon_model" );
        //$pokemon_model = Pokemon::whereIn( 'pokedex_no', array_keys( $all_pokemons ) )->get()->toArray();
        //\Debugbar::info( $pokemon_model );
        //\Debugbar::stopMeasure( "pokemon_model" );

        //if()
        return $items;
    }

    public function request( $page, $query, $limit, $debug = false ) {
        $message = 'TCG page: ' . $page . ', limit: ' . $limit;

        $cache_key = 'tcg-' . $limit . '-' . $page . '-' . $query;
        if ( Cache::has( $cache_key ) ) {
            $message .= ' using cache.';
            //\Debugbar::info( Cache::get( $cache_key ) );
            $body = Cache::get( $cache_key );
        } else {
            $headers = [
                'X-Api-Key' => 'ea92d5ff-9928-42ad-bd43-1dc3fc2c339b',
            ];

            $params = [
                'pageSize' => $limit,
                'page'     => $page,
            ];

            if ( '' !== $query ) {
                $params['q'] = $query;
            }

            $url      = 'https://api.pokemontcg.io/v2/cards?' . http_build_query( $params );
            $response = \Http::get( $url, $params );

            $client   = new \GuzzleHttp\Client();
            $request  = new \GuzzleHttp\Psr7\Request( 'GET', $url, $headers );
            $response = $client->send( $request );

            //$response = \Http::withHeaders( $headers )->post( $url, $params );
            //\Debugbar::info( 'response' );
            $status = $response->getStatusCode();
            $message .= ' using protocol: ' . $response->getProtocolVersion() . ', status: ' . $status . ' - ';

            if ( 429 === $status ) {
                $reset = date( 'm/d/Y', $response->getHeader( 'RateLimit-Reset' )[0] );
                $message .= ' Rate limit of ' . $response->getHeader( 'RateLimit-Limit' )[0] . ' exceeded. Resets on ' . $reset . '.';

                $body = false;
            } else if ( 404 === $status ) {
                $message .= ' not found.';

                $body = false;
            } else if ( 400 === $status ) {
                $message .= ' bad request.';

                $body = false;
            } else {
                $message .= ' received results.';
                //\Debugbar::info( $response );
                $body = $this->body( $response->getBody()->getContents() );
                //\Debugbar::info( $body );

                Cache::put( $cache_key, $body, 500000 ); //500000 minutes
            }
        }

        if ( $debug ) {
            \Debugbar::info( $message );
        }

        return $body;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Energy;
use App\Models\Pokemon;
use App\Models\Trainer;
use App\Services\API;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Cards extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function cards() {
        $card_model    = new Card();
        $pokemon_model = new Pokemon();
        $energy_model  = new Energy();
        $trainer_model = new Trainer();

        $api = new APITcg();

        $query = false;
        $query = $query ? $query : '';

        $cards = $api->get_cards( 1, $query );

        //\Debugbar::info( $cards );
        return view( 'pages.cards', compact( 'cards', 'card_model', 'pokemon_model', 'energy_model' ) );
    }

    public function delete( Card $card ) {
        $card->delete();

        return response()->json( null, 204 );
    }

    public function header_border() {
        return '<svg viewBox="0 0 1298 184" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M5.2384,8.41307068 C33.0353344,8.41307068 1223.20111,16.4795685 1266.07629,8.41307068 C1308.95148,0.346572876 1299.70242,3.8699161 1262.83189,12.2683899 C1225.96136,20.6668638 1138.18032,109.327905 1114.88363,135.378299 C1081.06124,173.19855 1045.82536,180.41156 1018.02842,180.41156 C995.706731,180.41156 -7.68278925,177.500283 5.2384,176.508851 C8.40781143,176.265665 -0.236841313,8.41307068 5.2384,8.41307068 Z" id="Path" stroke="#000000" stroke-width="6"></path></svg>';
    }

    public function index() {
        return Card::all();
    }

    public function main_card_class() {
        $supertype = $this->attributes['supertype'];
        $classes   = ['tcg', $supertype];

        $subtypes = $this->attributes['subtypes'];

        if ( is_array( $subtypes ) ) {
            foreach ( $subtypes as $type ) {
                $classes[] = Str::camel( $type );
            }
        } else {
            $classes[] = Str::camel( $subtypes );
        }

        $classes = [$this->attributes['slug'], Str::slug( implode( ' ', $classes ), ' ' )];

        if ( TypeHelper::type_pokemon( $supertype ) ) {
            $count = 0;

            if ( array_key_exists( 'attacks', $this->attributes ) ) {
                $attacks = $this->attributes['attacks'];
                $count += count( (array) json_decode( $attacks ) );
            }

            if ( array_key_exists( 'abilities', $this->attributes ) ) {
                $abilities = $this->attributes['abilities'];
                $count += count( (array) json_decode( $abilities ) );
            }

            $classes[] = 'middle-count-' . $count;
        } else if ( TypeHelper::type_trainer( $supertype ) ) {
            if ( $this->attributes['hp'] ) {
                $classes[] = 'has-hp';
            } else {
                $classes[] = 'no-hp';
            }
        }

        $classes[] = 'col col-4';
        return implode( ' ', $classes );
    }

    public function main_image_class() {
        $classes = ['main-image'];

        if ( array_key_exists( 'evolves_from', $this->attributes ) && $this->attributes['evolves_from'] ) {
            $classes[] = 'evolvable';
        }

        return implode( ' ', $classes );
    }

    /**
     * Get the single pokemon that has the extra information
     */
    public function pokemons() {
        return $this->belongsToMany( Pokemon::class, 'pokemons_cards', 'card_id', 'pokemon_id' );
    }

    public function show( Card $card ) {
        return $card;
    }

    public function special() {
        $special  = false;
        $subtypes = json_decode( $this->attributes['subtypes'] );

        if ( count( $subtypes ) > 1 ) {
            array_shift( $subtypes );

            $special = implode( ' ', $subtypes );
        }
        return $special;
    }

    public function update( Request $request, Card $card ) {
        $card->update( $request->all() );

        return response()->json( $card, 200 );
    }
}

<?php

namespace App\Services\TCG;

use App\Helpers\TextHelper;
use App\Helpers\TypeHelper;
use App\Models\Card;
use App\Models\Pokemon;
use App\Models\PokemonCard;
use App\Services\Pokedex;
use Illuminate\Support\Str;

class Card {
    public function attacks( $api_attacks ) {
        $attacks = [];
        foreach ( $api_attacks as $attack ) {
            $slug = Str::slug( $attack->name, '-' );

            $attack->note = false;

            // Remove (NOTE) and add *Note: NOTE
            $note = '(You can\'t use more than 1 GX attack in a game.)';
            if ( strpos( $attack->text, $note ) !== false ) {
                $attack->text = str_replace( $note, '', $attack->text );
                $note         = str_replace( '(', '', $note );
                $note         = str_replace( ')', '', $note );
                $attack->note = '*Note: ' . $note . '';
            }

            $attacks[$slug] = $attack;
        }

        return $attacks;
    }

    public function costs( $info, $type ) {
        if ( ! property_exists( $info, $type ) ) {
            return null;
        }

        return $info->$type;
    }

    public function db( $all_info ) {
        $info     = $all_info['card_info'];
        $deck     = $all_info['deck'];
        $pokemons = $all_info['pokemons'];

        $name = TextHelper::canadian_spelling( $info->name );
        $slug = Str::slug( $name, '-' );

        $name = TextHelper::canadian_spelling( $info->name );
        $slug = Str::slug( $name, '-' );

        $type      = $info->supertype;
        $type_slug = Str::lower( $type );

        $card = Card::firstOrNew( ['slug' => $slug] );
        if ( $card->exists ) {
            // "firstOrCreate" didn't find the card in the DB, so it created it.

            $card->api = json_encode( $info );

            //\Debugbar::info( $info );
            $card->carddeck_id    = $info->id;
            $card->slug           = $slug;
            $card->name           = $this->name( $name );
            $card->number         = $info->number;
            $card->image_official = $info->images->large;

            if ( property_exists( $info, 'evolvesFrom' ) ) {
                $card->evolves_from_id = $this->evolution( $info->evolvesFrom );
            }

            if ( property_exists( $info, 'evolvesTo' ) ) {
                $card->evolves_to_id = $this->evolution( $info->evolvesTo );
            }

            $card->text      = '';
            $card->supertype = Str::slug( $type );

            $subtypes = [];
            if ( property_exists( $info, 'subtypes' ) ) {
                $subtypes = $info->subtypes;
            }
            $card->subtypes = json_encode( $subtypes );

            if ( property_exists( $info, 'hp' ) ) {
                $card->hp = $info->hp;
            }

            if ( TypeHelper::type_pokemon( $type ) ) {
                $card->supertype = 'pokemon';

                if ( property_exists( $info, 'evolveTo' ) ) {
                    $card->evolve_to = $info->evolveTo;
                }

                if ( property_exists( $info, 'attacks' ) ) {
                    $attacks = $this->attacks( $info->attacks );
                } else {
                    $attacks = [];
                }
                $card->attacks = json_encode( $attacks );

                if ( property_exists( $info, 'abilities' ) ) {
                    $abilities = $this->attacks( $info->abilities );
                } else {
                    $abilities = [];
                }
                $card->abilities = json_encode( $abilities );

                $card->types = json_encode( $info->types );

                if ( property_exists( $info, 'flavorText' ) ) {
                    $card->text = strip_tags( $info->flavorText );
                }

                if ( $card->id && $pokemons && is_array( $pokemons ) ) {
                    foreach ( $pokemons as $pokemon ) {
                        //$card->evolves_to_id = $pokemon->id;
                        /*$pokemonCard         = PokemonCard::firstOrNew( [
                        'card_id'    => $card->id,
                        'pokemon_id' => $pokemon->id,
                        ] )->save();*/

                        $card->text = $pokemon->text;
                    }

                    // Remove footer text for tag teams
                    if ( count( $pokemons ) > 1 ) {
                        $card->text = '';
                    }
                }
            } else if ( TypeHelper::type_trainer( $type ) ) {
                $card->text     = implode( '<br>', $info->rules );
                $card->subtypes = $this->subtypes( $subtypes, 'Trainer' );
            } else if ( TypeHelper::type_energy( $type ) ) {
                $card->text     = implode( '<br>', $info->rules );
                $card->subtypes = $this->subtypes( $subtypes, 'Trainer' );
            }

            $card->text = trim( strip_tags( str_replace( ["/\s\s+/", "\n", '\f', '\r', ''], " ", $card->text ) ) );

            $card->weaknesses  = json_encode( $this->costs( $info, 'weaknesses' ) );
            $card->resistances = json_encode( $this->costs( $info, 'resistances' ) );
            $card->retreat     = json_encode( $this->costs( $info, 'retreatCost' ) );

            $card->deck_id = $deck->id;

            $card->save();
            //\Debugbar::info( $card );
        }

        return $card;
    }

    public function evolution( $name ) {
        if ( is_array( $name ) ) {
            $name = $name[0];
        }

        if ( 'Mysterious Fossil' === $name ) {
            $slug                = Str::slug( $name, '-' );
            $pokemon             = Pokemon::firstOrNew( ['slug' => $slug] );
            $pokemon->name       = $name;
            $pokemon->pokedex_no = $slug;
            $pokemon->api        = json_encode( [] );

            $pokemon->save();
        } else {
            $pokemon = ( new Pokemon() )->db( $name );
        }

        if ( ! $pokemon ) {
            return null;
        }

        return $pokemon->id;
    }

    public function name( $name ) {
        if ( strpos( $name, '-GX' ) !== false ) {
            $name = str_replace( '-GX', '', $name );
        } else if ( strpos( $name, '-EX' ) !== false ) {
            $name = str_replace( '-EX', '', $name );
        }

        return $name;
    }

    public function subtypes( $api_subtypes, $default = '' ) {
        if ( ! $api_subtypes ) {
            return $default;
        } else if ( count( $api_subtypes ) == 1 ) {
            return $api_subtypes[0];
        }

        array_shift( $api_subtypes );

        $subtypes = [];
        foreach ( $api_subtypes as $subtype ) {
            $name = $subtype;

            if ( 'GX' !== $name && 'EX' !== $name ) {
                $name = Str::title( $name );
            }

            $subtypes[] = $name;
        }

        return implode( ' ', $subtypes );
    }
}

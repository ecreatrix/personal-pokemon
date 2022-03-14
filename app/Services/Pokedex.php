<?php
namespace App\Services;

use App\Models\Ability;
use App\Models\Move;
use App\Models\MoveType;
use App\Models\Pokemon;
use App\Models\PokemonAbility;
use App\Models\PokemonMove;
use App\Models\PokemonRegion;
use App\Models\PokemonType;
use App\Models\PokemonVariety;
use App\Models\Region;
use App\Models\Type;
use App\Models\Variety;
use Illuminate\Support\Str;

class Pokedex {
    private static $main_pokemon_columns = ['pokemons.id', 'pokedex_no', 'name', 'slug', 'colour', 'image_slug', 'text_y', 'text_x', 'api_text'];

    public function aacreate_pokemon_custom( $species, $variety_info, $pokedex_no_original, $search_id = false ) {
        $pokedex_no_original = $species->pokedex_numbers[0]->entry_number;
        $pokedex_no          = Naming::pad_pokedex_no( $pokedex_no_original );

        if ( $search_id ) {
            // Variety
            $info    = API::poke_request( $search_id );
            $pokemon = Pokemon::firstOrNew( ['slug' => $info->name] );
        } else {
            // Default Pokemon
            $info    = API::poke_request( $pokedex_no_original );
            $pokemon = Pokemon::firstOrNew( ['pokedex_no' => $pokedex_no] );
        }

        $species_name = $info->species->name;
        $name         = $species_name;
        //clock( $info );

        //clock( $pokemon->toArray() );
        //clock( $info->name . ' ' . $species_name );
        //clock( $pokemon->toArray() );

        //clock( $pokemon->toArray() );
        //if ( ! $search_id ) {
        //$this->pokedexes( $pokemon, $species );
        //}

        //clock( $this->get_image_slug( $species, $variety_info, $pokedex_no_original, $search_id ) );

        $pokemon->save();
        return $pokemon;
    }

    public function create_ability( $slug, $url = false ) {
        if ( $url ) {
            $ability_id = Naming::url_id( $url, 'ability' );
            $ability    = Ability::firstOrNew( ['number' => $ability_id] );
        } else {
            $ability = Ability::firstOrNew( ['slug' => $slug] );
        }

        if ( ! $ability->exists ) {
            $info = API::poke_request( $slug, 'ability' );

            if ( ! $info ) {
                \Debugbar::info( 'Ability not found in API: ' . $slug );
                return false;
            }

            $ability->number      = $info->id;
            $ability->source      = 'pokedex';
            $ability->name        = Naming::english_by_key( $info->names );
            $ability->slug        = Str::slug( $info->name );
            $ability->generation  = Naming::generation_no( $info->generation->name );
            $ability->description = Naming::english_by_key( $info->flavor_text_entries, 'flavor_text' );

            $ability->save();
        }

        return $ability;
    }

    public function create_move( $slug, $url = false ) {
        if ( $url ) {
            $move_id = Naming::url_id( $url, 'move' );
            $move    = Move::firstOrNew( ['number' => $move_id] );
        } else {
            $move = Move::firstOrNew( ['slug' => $slug] );
        }

        if ( ! $move->exists ) {
            $info = API::poke_request( $slug, 'move' );

            if ( ! $info ) {
                \Debugbar::info( 'Move not found in API: ' . $slug );
                return false;
            }

            //\Debugbar::info( $info );
            $move->number      = $info->id;
            $move->source      = 'pokedex';
            $move->name        = Naming::english_by_key( $info->names );
            $move->description = Naming::english_by_key( $info->flavor_text_entries, 'flavor_text' );
            $move->type        = $info->type->name;

            $move->slug       = Str::slug( $info->name );
            $move->generation = Naming::generation_no( $info->generation->name );

            $move->class = $info->damage_class->name;

            $move->save();
        }

        return $move;
    }

    public function create_pokemon( $args, $species, $info, $variety, $url_id = false, $default = true ) {
        $pokedex_no_original = $species->pokedex_numbers[0]->entry_number;
        $pokedex_no          = Naming::pad_pokedex_no( $pokedex_no_original );

        $pokemon = Pokemon::firstOrNew( $args );

        //clock( $pokemon->toArray() );
        if ( ! $pokemon->exists ) {
            //clock( $info );
            $species_name = $species->name;
            $name         = $info->name;

            $pokemon->slug = $this->get_pokemon_slug( $name );
            $pokemon->name = $this->get_pokemon_name( $name, $pokedex_no );

            //clock( $species_name . ' ' . $name . ' ' . $pokedex_no . ' ' . $url_id );

            $pokemon->pokedex_no = $pokedex_no;

            $pokemon->source = 'pokedex';

            $pokemon->api = json_encode( $info );

            $pokemon->sprites = json_encode( $info->sprites );

            $pokemon->height = $info->height;
            $pokemon->weight = $info->weight;

            $evolution               = $this->evolution( $pokemon, $species );
            $pokemon->previous_stage = $evolution['previous_stage'];
            $pokemon->next_stage     = $evolution['next_stage'];

            $api_text          = $this->get_pokemon_text( $pokemon, $name, $species->flavor_text_entries );
            $pokemon->api_text = $api_text;

            if ( $species->color ) {
                $pokemon->colour = $species->color->name;
            }

            $pokemon->generation = Naming::generation_no( $species->generation->name );

            if ( $species->habitat ) {
                $pokemon->habitat = $species->habitat->name;
            }

            if ( $species->genera ) {
                $pokemon->genus = Naming::english_by_key( $species->genera, 'genus' );
            }

            $pokemon->genderable = $species->has_gender_differences;
            $pokemon->pokeapi_id = $url_id;

            $stats = $this->get_pokemon_stats( $info );
            foreach ( $stats as $key => $stat ) {
                $pokemon->$key = $stat;
            }

            $pokemon->variety_id = $variety->id;

            $pokemon->image_slug = $this->get_image_slug( $species_name, $name, $pokedex_no_original, $default );

            $pokemon->save();

            foreach ( $info->moves as $move_info ) {
                $move = $this->create_move( $move_info->move->name, $move_info->move->url );
                //$this->create_pokemon_move( $pokemon, $move );
            }

            foreach ( $info->types as $type_info ) {
                $type = $this->create_type( $type_info->type->name, $type_info->type->url );
                $this->create_pokemon_type( $pokemon, $type );
            }

            foreach ( $info->abilities as $ability_info ) {
                $ability = $this->create_ability( $ability_info->ability->name, $ability_info->ability->url );
                $this->create_pokemon_ability( $pokemon, $ability );
            }

            //if ( ! $search_id ) {
            $this->pokedexes( $pokemon, $species );
            //}
        }

        return $pokemon;
    }

    public function create_pokemon_ability( $pokemon, $ability ) {
        $pokemonAbility = PokemonAbility::firstOrNew( ['ability_id' => $ability->id, 'pokemon_id' => $pokemon->id] );

        if ( ! $pokemonAbility->exists ) {
            $pokemonAbility->ability_id = $ability->id;
            $pokemonAbility->pokemon_id = $pokemon->id;

            $pokemonAbility->save();
        }

        return $pokemonAbility;
    }

    public function create_pokemon_from_id( $pokedex_no ) {
        $species  = Api::poke_request( $pokedex_no, 'pokemon-species' );
        $pokemons = [];

        if ( $species ) {
            //clock( $species->varieties );
            foreach ( $species->varieties as $variety_info ) {
                $url_id = Naming::url_id( $variety_info->pokemon->url, 'pokemon' );

                $pokedex_no_original = $species->pokedex_numbers[0]->entry_number;
                $args                = ['pokedex_no' => Naming::pad_pokedex_no( $pokedex_no_original )];

                // Default Pokemon
                $info = API::poke_request( $pokedex_no_original );
                if ( $url_id ) {
                    // Variety
                    $info = API::poke_request( $url_id );
                }

                $species_name = $info->species->name;
                //clock( $pokemon->toArray() );

                //$pokemons[] = $this->create_pokemon( $species, $variety_info, $info, $pokemon, $url_id );

                //clock( $info );
                $name = $info->name;

                /*if ( 'wormadam-plant' === $name ) {
                $custom_name       = 'wormadam';
                $custom_info       = $info;
                $custom_info->name = $custom_name;
                $args['slug']      = $custom_info->name;

                $variety    = $this->create_variety( $species_name, $custom_name, true );
                $pokemons[] = $this->create_pokemon( $args, $species, $custom_info, $variety, $url_id );
                //clock( ' •' . $name . ' pokedex: ' . $pokedex_no . ' api: ' . $url_id );
                }*/

                //clock( $species_name );

                if ( count( $info->forms ) > 1 && 414 != $pokedex_no_original ) {
                    $form_count = 0;
                    foreach ( $info->forms as $form_info ) {
                        $form_count++;
                        $name = $form_info->name;

                        $form_url_id       = Naming::url_id( $form_info->url, 'pokemon-form' );
                        $form_info_request = API::poke_request( $form_url_id, 'pokemon-form' );

                        $info         = (object) array_merge( (array) $info, (array) $form_info_request );
                        $args['slug'] = $info->name;
                        //clock( ' +' . $name . ' pokedex: ' . $pokedex_no . ' api: ' . $form_url_id );

                        if ( 1 == $form_count ) {
                            $default = true;
                        } else {
                            $default = false;
                        }

                        $variety    = $this->create_variety( $species_name, $name, $default );
                        $pokemons[] = $this->create_pokemon( $args, $species, $info, $variety, $form_url_id, $default );
                        //clock( $form_info_request );
                        //$this->create_variety( $species_name, $pokemon_name, $form_info );
                    }
                } else {
                    $args['slug'] = $info->name;
                    $default      = $variety_info->is_default;
                    $variety      = $this->create_variety( $species_name, $name, $default );
                    $pokemons[]   = $this->create_pokemon( $args, $species, $info, $variety, $url_id, $default );
                }
            }
        }

        return $pokemons;
    }

    public function create_pokemon_move( $pokemon, $move ) {
        //clock( $pokemon );
        //clock( $move );
        $pokemonMove = PokemonMove::firstOrNew( ['move_id' => $move->id, 'pokemon_id' => $pokemon->id] );

        if ( ! $pokemonMove->exists ) {
            $pokemonMove->move_id    = $move->id;
            $pokemonMove->pokemon_id = $pokemon->id;

            $pokemonMove->save();
        }

        return $pokemonMove;
    }

    public function create_pokemon_region( $pokemon, $region_slug, $primary = false ) {
        $region = $this->create_region( $region_slug );

        $pokemonRegion = PokemonRegion::firstOrNew( ['region_id' => $region->id, 'pokemon_id' => $pokemon->id] );

        if ( ! $pokemonRegion->exists ) {
            $pokemonRegion->region_id  = $region->id;
            $pokemonRegion->pokemon_id = $pokemon->id;
            $pokemonRegion->primary    = $primary;

            $pokemonRegion->save();
        }

        return $pokemonRegion;
    }

    public function create_pokemon_type( $pokemon, $type ) {
        $pokemonType = PokemonType::firstOrNew( ['type_id' => $type->id, 'pokemon_id' => $pokemon->id] );

        if ( ! $pokemonType->exists ) {
            $pokemonType->type_id    = $type->id;
            $pokemonType->pokemon_id = $pokemon->id;

            $pokemonType->save();
        }

        return $pokemonType;
    }

    public function create_region( $slug ) {
        $region = Region::firstOrNew( ['slug' => $slug] );

        if ( ! $region->exists ) {
            $info = API::poke_request( $slug, 'region' );

            if ( ! $info ) {
                \Debugbar::info( 'Region not found in API: ' . $slug );
                return $region;
            }

            $region_id = $info->id;

            $region->name       = Str::title( $info->name );
            $region->slug       = Str::slug( $info->name );
            $region->generation = Naming::generation_no( $info->main_generation->name );
            $region->number     = $region_id;
            $region->source     = 'pokedex';

            $locations = [];
            foreach ( $info->locations as $location ) {
                $name             = $location->name;
                $locations[$name] = Str::title( $name );
            }

            $region->locations = json_encode( $locations );

            $region->api = json_encode( $info );

            $region->save();
        }

        return $region;
    }

    public function create_region_custom( $slug = 'unknown', $region_id = 99 ) {
        $region = Region::firstOrNew( ['slug' => $slug] );

        if ( ! $region->exists ) {
            $region->name       = Str::title( $slug );
            $region->slug       = Str::slug( $slug );
            $region->generation = false;
            $region->number     = $region_id;
            $region->source     = 'custom';

            $region->locations = json_encode( [] );

            $region->api = json_encode( [] );

            $region->save();
        }

        return $region;
    }

    public function create_type( $slug, $url = false ) {
        if ( $url ) {
            $type_id = Naming::url_id( $url, 'type' );
            $type    = Type::firstOrNew( ['number' => $type_id] );
        } else {
            $type = Type::firstOrNew( ['slug' => $slug] );
        }

        if ( ! $type->exists ) {
            $info = API::poke_request( $slug, 'type' );

            if ( ! $info ) {
                \Debugbar::info( 'Type not found in API: ' . $slug );
                return false;
            }

            $type->number     = $info->id;
            $type->name       = Naming::english_by_key( $info->names );
            $type->slug       = Str::slug( $info->name );
            $type->generation = Naming::generation_no( $info->generation->name );
            $type->source     = 'pokedex';

            $type->double_damage_to   = $this->type_damage( $info->damage_relations->double_damage_to );
            $type->double_damage_from = $this->type_damage( $info->damage_relations->double_damage_from );
            $type->half_damage_to     = $this->type_damage( $info->damage_relations->half_damage_to );
            $type->half_damage_from   = $this->type_damage( $info->damage_relations->half_damage_from );
            $type->no_damage_to       = $this->type_damage( $info->damage_relations->no_damage_to );
            $type->no_damage_from     = $this->type_damage( $info->damage_relations->no_damage_from );

            $type->save();
        }

        return $type;
    }

    public function create_variety( $species_name, $pokemon_name, $is_default = false ) {
        if ( $is_default ) {
            $slug = 'default';
        } else {
            $slug = $this->get_pokemon_species_slug( $species_name, $pokemon_name );
        }

        $variety = Variety::firstOrNew( ['slug' => $slug] );

        if ( ! $variety->exists ) {
            $variety->source = 'pokedex';

            $variety->name = Str::title( $slug );
            $variety->slug = Str::slug( $slug );

            $variety->save();
        }

        return $variety;
    }

    public function evolution( $pokemon, $species ) {
        $pokemon_no     = ltrim( $pokemon->pokedex_no, '0' );
        $previous_stage = false;
        $next_stage     = false;

        if ( property_exists( $species, 'evolves_from_species' ) && null != $species->evolves_from_species ) {
            $evolves_from = Naming::url_id( $species->evolves_from_species->url, '-species' );
            $evolves_from = API::poke_request( $evolves_from );

            $previous_stage = Naming::pad_pokedex_no( $evolves_from->id );
        }

        $evolution_chain_id = Naming::url_id( $species->evolution_chain->url, 'chain' );

        $evolution = API::poke_request( $evolution_chain_id, 'evolution-chain' );

        if ( $evolution && property_exists( $evolution, 'chain' ) && null != $evolution->chain->evolves_to ) {
            // Go through evolution chain to find next one
            $starting    = Naming::url_id( $evolution->chain->species->url, '-species' );
            $evolves_to1 = false;

            if ( ! empty( $evolution->chain->evolves_to ) ) {
                $evolves_to1 = Naming::url_id( $evolution->chain->evolves_to[0]->species->url, '-species' );
            }

            $evolves_to2 = false;

            if ( ! empty( $evolution->chain->evolves_to[0]->evolves_to ) ) {
                $evolves_to2 = Naming::url_id( $evolution->chain->evolves_to[0]->evolves_to[0]->species->url, '-species' );
            }

            $evolves_to3 = false;

            if ( ! empty( $evolution->chain->evolves_to[0]->evolves_to[0]->evolves_to ) ) {
                $evolves_to3 = Naming::url_id( $evolution->chain->evolves_to[0]->evolves_to[0]->evolves_to[0]->evolves_to->species->url, '-species' );
            }

            $next_stage = false;
            if ( $starting === $pokemon_no ) {
                $next_stage = $evolves_to1;
            } else if ( $evolves_to1 === $pokemon_no ) {
                $next_stage = $evolves_to2;
            } else if ( $evolves_to2 === $pokemon_no ) {
                $next_stage = $evolves_to3;
            }

            if ( $next_stage ) {
                $next_stage = API::poke_request( $next_stage );

                if ( $next_stage && property_exists( $next_stage, 'id' ) ) {
                    $next_stage = Naming::pad_pokedex_no( $next_stage->id );
                }
            }
        }

        return ['previous_stage' => $previous_stage, 'next_stage' => $next_stage];
    }

    public function get_image_slug( $species_name, $pokemon_name, $pokedex_no_original, $default ) {
        $pokedex_no = Naming::pad_pokedex_no( $pokedex_no_original );

        if ( $default ) {
            // Default Pokemon
            $pokemon_name = str_replace( '-plant', '_plant', $pokemon_name );
            $image_slug   = $pokemon_name;
        } else {
            // Variety/form
            $image_slug = str_replace( $species_name . '-', '', $pokemon_name );
            $image_slug = ucwords( str_replace( '-', ' ', $image_slug ) );
            $image_slug = ucfirst( $species_name ) . '_' . str_replace( ' ', '-', $image_slug );
        }

        $image_slug = str_replace( '_Totem-Alola', '_Alola', $image_slug );

        $image_slug = str_replace( '_Starter', '', $image_slug );
        $image_slug = str_replace( '_Battle-bond', '', $image_slug );
        $image_slug = str_replace( '_Totem-', '_', $image_slug );
        $image_slug = str_replace( '_Totem', '', $image_slug );

        $image_slug = str_replace( 'Mr.', 'Mr', $image_slug );

        $image_slug = str_replace( '\'ed', 'ed', $image_slug );

        $image_slug = str_replace( '711Gourgeist_Super', '711Gourgeist', $image_slug );
        $image_slug = str_replace( '711Gourgeist_Large', '711Gourgeist', $image_slug );
        $image_slug = str_replace( '711Gourgeist_Small', '711Gourgeist', $image_slug );

        $image_slug = str_replace( '711Pumpkaboo_Super', '711Pumpkaboo', $image_slug );
        $image_slug = str_replace( '711Pumpkaboo_Large', '711Pumpkaboo', $image_slug );
        $image_slug = str_replace( '711Pumpkaboo_Small', '711Pumpkaboo', $image_slug );

        $image_slug = str_replace( '718Zygarde_10-Power-Construct', '718Zygarde_10', $image_slug );
        $image_slug = str_replace( '718Zygarde_50-Power-Construct', '718Zygarde_50', $image_slug );

        $image_slug = str_replace( '774Minior_Orange-Meteor', '774Minior_Meteor', $image_slug );
        $image_slug = str_replace( '774Minior_Yellow-Meteor', '774Minior_Meteor', $image_slug );
        $image_slug = str_replace( '774Minior_Green-Meteor', '774Minior_Meteor', $image_slug );
        $image_slug = str_replace( '774Minior_Blue-Meteor', '774Minior_Meteor', $image_slug );
        $image_slug = str_replace( '774Minior_Indigo-Meteor', '774Minior_Meteor', $image_slug );
        $image_slug = str_replace( '774Minior_Violet-Meteor', '774Minior_Meteor', $image_slug );

        $image_slug = str_replace( '741Oricorio_Pa\'u', '741Oricorio_Pau', $image_slug );

        $image_slug = str_replace( '744Rockruff_Own-Tempo', '744Rockruff', $image_slug );

        $image_slug = str_replace( '800Necrozma_Dusk', '800Necrozma_Dusk-Mane', $image_slug );
        $image_slug = str_replace( '800Necrozma_Dawn', '800Necrozma_Dawn-Wings', $image_slug );

        $image_slug = str_replace( '801Magearna_Original', '801Magearna_Original-Color', $image_slug );

        $image_slug = str_replace( '849Toxtricity_Low-Key-Gmax', '849Toxtricity_Gmax', $image_slug );
        $image_slug = str_replace( '849Toxtricity_Amped-Gmax', '849Toxtricity_Gmax', $image_slug );

        return $pokedex_no . $image_slug;
    }

    public function get_pokemon_name( $name ) {
        $name = str_replace( 'flabebe', 'Flabébé', $name );
        $name = str_replace( 'farfetchd', 'farfetch\'d', $name );
        $name = str_replace( 'oricorio pau', 'oricorio pa\'u', $name );

        $name = preg_replace( '/(.*)-mega/i', 'Mega $1', $name );
        $name = preg_replace( '/(.*)-gmax/i', 'Gigantamax $1', $name );
        $name = preg_replace( '/(.*)-alola/i', 'Alolan $1', $name );
        $name = preg_replace( '/(.*)-galar/i', 'Galarian $1', $name );
        $name = preg_replace( '/(.*)-hisui/i', 'Hisuian $1', $name );
        //$name = str_replace( '-starter', '', $name );
        $name = preg_replace( '/(.*)-totem/i', 'Totem $1', $name );

        $name = str_replace( 'mr-', 'mr. ', $name );

        if ( '032' === $pokedex_no ) {
            $name = preg_replace( '/(.*)-m/i', '$1 ♂', $name );
        }
        if ( '029' === $pokedex_no ) {
            $name = preg_replace( '/(.*)-f/i', '$1 ♀', $name );
        }

        $name = str_replace( '-', ' ', $name );

        $name = str_replace( 'Porygon Z', 'Porygon-Z', $name );

        return Str::title( $name );
    }

    public function get_pokemon_slug( $name ) {
        $name = str_replace( 'Flabébé', 'Flabebe', $name );

        $name = str_replace( ' ♂', '-male', $name );
        $name = str_replace( ' ♀', '-female', $name );

        $name = str_replace( ' ', '-', $name );

        return Str::slug( $name );
    }

    public function get_pokemon_species_name( $species_name ) {
    }

    public function get_pokemon_species_slug( $species_name, $name ) {
        $slug = $this->get_pokemon_slug( $species_name );
        $slug = str_replace( $slug . '-', '', $name );

        $slug = str_replace( 'mega-x', 'mega', $slug );
        $slug = str_replace( 'mega-y', 'mega', $slug );

        return $slug;
    }

    public function get_pokemon_stats( $info ) {
        $stats = [];
        foreach ( $info->stats as $stat ) {
            $name         = str_replace( '-', '_', $stat->stat->name, );
            $stats[$name] = $stat->base_stat;
        }

        return $stats;
    }

    public function get_pokemon_text( $pokemon, $name, $text ) {
        $text = Naming::english_by_key( $text, 'flavor_text' );

        $text = str_replace( str::upper( $name ), str::title( $name ), $text );
        $text = str_replace( 'MAWHILE', 'Mawile', $text );

        $previous_stage = $pokemon->previous_stage();
        if ( $previous_stage ) {
            $name = $previous_stage->name;
            $text = str_replace( str::upper( $name ), str::title( $name ), $text );
        }

        return $text;
    }

    public function pokedexes( $pokemon, $species ) {
        $pokedexes = $species->pokedex_numbers;

        $custom            = [];
        $main_region_found = false;
        foreach ( $pokedexes as $pokedex ) {
            $pokedex_id = Naming::url_id( $pokedex->pokedex->url, 'pokedex' );

            if ( 1 != $pokedex_id ) {
                // First region is the national system so skip it
                $pokedex_info = Api::poke_request( $pokedex_id, 'pokedex' );
                $pokedex_name = Naming::english_by_key( $pokedex_info->names );
                $pokedex_slug = Str::slug( $pokedex_name );

                $name = $pokedex_name;
                $slug = $pokedex_slug;

                if ( $pokedex_info->region ) {
                    $region_id   = Naming::url_id( $pokedex_info->region->url, 'region' );
                    $region_info = Api::poke_request( $region_id, 'region' );

                    $region_name = Naming::english_by_key( $region_info->names );
                    $region_slug = Str::slug( $region_name );

                    $name = $region_name;
                    $slug = $region_slug;

                    if ( ! $main_region_found ) {
                        // Will give first appearance of pokemon on pokedex
                        $this->create_pokemon_region( $pokemon, $slug, true );

                        $main_region_found = true;
                    } else {
                        $this->create_pokemon_region( $pokemon, $slug );
                    }

                }
            }
        }
    }

    public function type_damage( $types ) {
        $custom = [];

        foreach ( $types as $info ) {
            $name = $info->name;

            $name = Str::title( $name );
            $slug = Str::slug( $name );

            $custom[] = ['slug' => $slug, 'name' => $name];
        }

        return json_encode( $custom );
    }
}
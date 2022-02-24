<?php
namespace App\Services;

use App\Models\Ability;
use App\Models\Move;
use App\Models\MoveType;
use App\Models\Pokemon;
use App\Models\PokemonAbility;
use App\Models\PokemonForm;
use App\Models\PokemonMove;
use App\Models\PokemonRegion;
use App\Models\PokemonType;
use App\Models\PokemonVariety;
use App\Models\Region;
use App\Models\Type;
use App\Models\Variety;
use Illuminate\Support\Str;

class Pokedex {
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

    public function create_form( $pokemon, $key, $info ) {
        $info_id = Naming::url_id( $info->url, 'form' );
        $slug    = $info->name;

        $form = PokemonForm::firstOrNew( ['slug' => $slug, 'pokemon_id' => $pokemon->id] );
        if ( $info_id && ! $form->exists ) {
            $info = API::poke_request( $info_id, 'pokemon-form' );

            if ( ! $info ) {
                \Debugbar::info( 'Form not found in API: ' . $slug );
                return false;
            }

            $form->source     = 'pokedex';
            $form->name       = Str::title( $slug );
            $form->pokemon_id = $pokemon->id;
            $form->slug       = Str::slug( $slug );

            $form->primary = false;
            if ( 0 === $key ) {
                $form->primary = true;
            }

            $form->save();
        }

        return $form;
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

    public function create_pokemon( $species, $variety_info, $pokedex_no_original, $search_id = false ) {
        $pokedex_no_original = $species->pokedex_numbers[0]->entry_number;
        $pokedex_no          = Naming::pad_pokedex_no( $pokedex_no_original );

        if ( $search_id ) {
            // Variety
            $info    = API::poke_request( $search_id );
            $pokemon = Pokemon::firstOrNew( ['slug' => $info->name] );

            $species_name = $info->species->name;
            $name         = Str::title( $info->name );
        } else {
            // Default Pokemon
            $info    = API::poke_request( $pokedex_no_original );
            $pokemon = Pokemon::firstOrNew( ['pokedex_no' => $pokedex_no] );

            $species_name = $info->species->name;
            $name         = Str::title( $species_name );
        }

        if ( ! $pokemon->exists ) {
            if ( ! $info ) {
                \Debugbar::info( 'Pokemon not found in API: ' . $search_id );
                return false;
            }

            $slug = Str::slug( $name );

            $name = str_replace( '-m', ' ♂', $name );
            $name = str_replace( '-f', ' ♀', $name );
            $name = str_replace( '-', ' ', $name );
            $name = str_replace( 'Porygon Z', 'Porygon-Z', $name );

            $pokemon->slug = $slug;
            $pokemon->name = $name;

            $pokemon->pokedex_no = $pokedex_no;

            $pokemon->source = 'pokedex';

            $pokemon->api = json_encode( $info );

            $pokemon->sprites = json_encode( $info->sprites );

            $pokemon->height = $info->height;
            $pokemon->weight = $info->weight;

            $pokemon->image_slug = $this->get_image_slug( $species, $variety_info, $pokedex_no_original, $search_id );

            $evolution               = $this->evolution( $pokemon, $species );
            $pokemon->previous_stage = $evolution['previous_stage'];
            $pokemon->next_stage     = $evolution['next_stage'];

            $pokemon->text = $this->get_pokemon_text( $pokemon, $name, $species );

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

            $variety = $this->create_variety( $species_name, $variety_info );

            $pokemon->variety_id = $variety->id;

            ///clock( $pokemon->toArray() );
            $pokemon->save();
            //clock( $info );
            foreach ( $info->moves as $move ) {
                $move = $this->create_move( $move->move->name, $move->move->url );
                $this->create_pokemon_move( $pokemon, $move );
            }

            foreach ( $info->types as $type ) {
                $type = $this->create_type( $type->type->name, $type->type->url );
                $this->create_pokemon_type( $pokemon, $type );
            }

            foreach ( $info->abilities as $ability ) {
                $ability = $this->create_ability( $ability->ability->name, $ability->ability->url );
                $this->create_pokemon_ability( $pokemon, $ability );
            }

            foreach ( $info->forms as $key => $form ) {
                $form = $this->create_form( $pokemon, $key, $form );
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

    public function create_pokemon_custom( $species, $variety_info, $pokedex_no_original, $search_id = false ) {
        $pokedex_no_original = $species->pokedex_numbers[0]->entry_number;
        $pokedex_no          = Naming::pad_pokedex_no( $pokedex_no_original );

        if ( $search_id ) {
            // Variety
            $info    = API::poke_request( $search_id );
            $pokemon = Pokemon::firstWhere( ['slug' => $info->name] );

            $species_name = $info->species->name;
            $name         = Str::title( $info->name );

        } else {
            // Default Pokemon
            $info    = API::poke_request( $pokedex_no_original );
            $pokemon = Pokemon::firstWhere( ['pokedex_no' => $pokedex_no] );

            $species_name = $info->species->name;
            $name         = Str::title( $species_name );
        }

        foreach ( $info->types as $type ) {
            $type = $this->create_type( $type->type->name, $type->type->url );
            $this->create_pokemon_type( $pokemon, $type );
        }

        //if ( ! $search_id ) {
        $this->pokedexes( $pokemon, $species );
        //}

        //clock( $this->get_image_slug( $species, $variety_info, $pokedex_no_original, $search_id ) );
        return $pokemon;
    }

    public function create_pokemon_move( $pokemon, $move ) {
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

    public function create_variety( $species_name, $variety_info ) {
        if ( $variety_info->is_default ) {
            $slug = 'basic';
        } else {
            $slug = str_replace( $species_name . '-', '', $variety_info->pokemon->name );
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

    public function get_image_slug( $species, $variety_info, $pokedex_no_original, $search_id ) {
        $pokedex_no_original = $species->pokedex_numbers[0]->entry_number;
        $pokedex_no          = Naming::pad_pokedex_no( $pokedex_no_original );

        if ( $search_id ) {
            // Variety
            $info = API::poke_request( $search_id );

            $species_name = $info->species->name;
            $name         = Str::title( $info->name );
            $image_slug   = str_replace( $species_name . '-', '', $variety_info->pokemon->name );
            $image_slug   = ucwords( str_replace( '-', ' ', $image_slug ) );
            $image_slug   = ucfirst( $species_name ) . '_' . str_replace( ' ', '-', $image_slug );
        } else {
            // Default Pokemon
            $info = API::poke_request( $pokedex_no_original );

            $species_name = $info->species->name;
            $name         = Str::title( $species_name );
            $image_slug   = $name;
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
        $name = str_replace( 'Flabebe', 'Flabébé', $name );
        $name = str_replace( '-m', ' ♂', $name );
        $name = str_replace( '-f', ' ♀', $name );
        $name = str_replace( '-', ' ', $name );
        $name = str_replace( 'Porygon Z', 'Porygon-Z', $name );

        return $name;
    }

    public function get_pokemon_text( $pokemon, $name, $species_info ) {
        $text = Naming::english_by_key( $species_info->flavor_text_entries, 'flavor_text' );

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
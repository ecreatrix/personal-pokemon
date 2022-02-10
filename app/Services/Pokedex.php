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
use App\Models\Region;
use App\Models\Type;
use App\Services\Naming;
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

    public function create_pokemon( $pokedex_no_original ) {
        $pokedex_no = Naming::pad_pokedex_no( $pokedex_no_original );
        $pokemon    = Pokemon::firstOrNew( ['pokedex_no' => $pokedex_no] );
        //\Debugbar::info( $pokemon );

        //if ( true || ! $pokemon->exists ) {
        if ( ! $pokemon->exists ) {
            $info = false;
            //\Debugbar::startMeasure( 'API' );
            $info    = API::poke_request( $pokedex_no_original );
            $species = Api::poke_request( $pokedex_no_original, 'pokemon-species' );
            //$slug = Naming::make_pokemon_slug( $info->name );
            //\Debugbar::stopMeasure( 'API' );

            if ( ! $info ) {
                \Debugbar::info( 'Pokemon not found in API: ' . $pokedex_no );
                return false;
            }
            //\Debugbar::info( 'get: ' . $pokedex_no );
            $name = Str::title( $info->name );
            $slug = Str::slug( $name );

            //\Debugbar::info( 'get: ' . $slug );
            $pokemon->slug = $slug;
            $pokemon->name = $name;

            $pokemon->source = 'pokedex';

            $pokemon->api = json_encode( $info );

            $pokemon->sprites = json_encode( $info->sprites );

            $pokemon->height = $info->height;
            $pokemon->weight = $info->weight;

            $pokemon = $this->evolution( $pokemon, $species );

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

            $pokemon->text = Naming::english_by_key( $species->names );

            $pokemon->save();

            $this->pokedexes( $pokemon, $species );

            //\Debugbar::info( $info );
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
        }

        return $pokemon;
    }

    public function create_pokemon_ability( $pokemon, $ability ) {
        $pokemonAbility = PokemonAbility::firstOrNew( ['ability_id' => $ability->id, 'pokemon_id' => $pokemon->id] );

        if ( ! $pokemonAbility->exists ) {
            //\Debugbar::info( 'PokemonAbility: ' . $pokemon->id . ' ' . $ability->id );
            $pokemonAbility->ability_id = $ability->id;
            $pokemonAbility->pokemon_id = $pokemon->id;

            $pokemonAbility->save();
        }

        return $pokemonAbility;
    }

    public function create_pokemon_move( $pokemon, $move ) {
        $pokemonMove = PokemonMove::firstOrNew( ['move_id' => $move->id, 'pokemon_id' => $pokemon->id] );

        if ( ! $pokemonMove->exists ) {
            //\Debugbar::info( 'PokemonMove: ' . $pokemon->id . ' ' . $move->id );
            $pokemonMove->move_id    = $move->id;
            $pokemonMove->pokemon_id = $pokemon->id;

            $pokemonMove->save();
        }

        return $pokemonMove;
    }

    public function create_pokemon_region( $pokemon, $region_slug, $primary = false ) {
        //\Debugbar::info( 'create_pokemon_region region_slug: ' . $region_slug . ' - ' . $pokemon->name );
        $region = $this->create_region( $region_slug );

        $pokemonRegion = PokemonRegion::firstOrNew( ['region_id' => $region->id, 'pokemon_id' => $pokemon->id] );

        if ( ! $pokemonRegion->exists ) {
            //\Debugbar::info( 'PokemonRegion: ' . $pokemon->id . ' ' . $region->id . ' - primary: ' . $primary );
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
            //\Debugbar::info( 'PokemonType: ' . $pokemon->id . ' ' . $type->id );
            //\Debugbar::info( $pokemonType );
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

            //\Debugbar::info( 'Region: ' . $info->name );
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

        //\Debugbar::info( $id . ': ' );
        //\Debugbar::info( $region );

        //\Debugbar::stopMeasure( 'regiondb' );
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

            //\Debugbar::info( 'get: ' . $info->name );

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

    public function evolution( $pokemon, $species ) {
        $pokemon_no = ltrim( $pokemon->pokedex_no, '0' );

        if ( property_exists( $species, 'evolves_from_species' ) && null != $species->evolves_from_species ) {
            $evolves_from = Naming::url_id( $species->evolves_from_species->url, '-species' );
            $evolves_from = API::poke_request( $evolves_from );

            $pokemon->previous_stage = Naming::pad_pokedex_no( $evolves_from->id );
        }

        $evolution_chain_id = Naming::url_id( $species->evolution_chain->url, 'chain' );

        $evolution = API::poke_request( $evolution_chain_id, 'evolution-chain' );

        if ( $evolution && property_exists( $evolution, 'chain' ) && null != $evolution->chain->evolves_to ) {
            //\Debugbar::info( $evolution->chain );
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
            //\Debugbar::info( $pokemon_no . ' ' . $starting . ' ' . $evolves_to1 . ' ' . $evolves_to2 . ' ' . $evolves_to3 );

            if ( $next_stage ) {
                //\Debugbar::info( ' next stage: ' . $next_stage );
                $next_stage = API::poke_request( $next_stage );
                //\Debugbar::info( $next_stage );
                if ( $next_stage && property_exists( $next_stage, 'id' ) ) {
                    $pokemon->next_stage = Naming::pad_pokedex_no( $next_stage->id );
                }
            }
        }

        return $pokemon;
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
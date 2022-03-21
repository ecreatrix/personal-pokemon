<?php

namespace App\Http\Livewire;

use App\Models\Pokemon;
use App\Models\Region;
use App\Models\Type;
use App\Models\Variety;
use App\Services\Download;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Pokedex extends Component {
    public $filter = [
        'varieties' => [
            1 => 1,
        ],
    ];

    public $regions;

    public $selected = [];

    public $types;

    public $update = [
        'colour' => true,
    ];

    protected $rules = [
        'update.colour'             => 'required|boolean',
        'filter.selected_regions'   => 'required|array',
        'filter.selected_numbers'   => 'required|array',
        'filter.selected_types'     => 'required|array',
        'filter.selected_varieties' => 'required|array',
    ];

    public function by_pokedex_no( $chosen_numbers, $array_key = false, $title = [], $filtered_pokemons = false ) {
        $selected = [];

        //clock( $chosen_numbers );
        foreach ( $chosen_numbers as $start => $value ) {
            $end = $start + 199;
            $end = $start + 3;

            if ( ! $filtered_pokemons ) {
                $pokemons = Pokemon::rangedCached( $start, $end );
            } else {
                // clock( $filtered_pokemons->get() );
                $pokemons = $filtered_pokemons->where( 'pokedex_no', '>=', $start )->where( 'pokedex_no', '<=', $end );
                // clock( $pokemons->get() );
            }

            $title[] = 'Range: ' . $start . ' to ' . $end;
            $title   = Str::title( implode( ', ', $title ) );

            $new_array_key = $start;
            if ( $array_key ) {
                $new_array_key = $array_key . '_' . $start;
            }

            $selected[$new_array_key] = [
                'title'    => $title,
                'slug'     => Str::slug( $title ),
                'pokemons' => $pokemons->get(),
            ];
        }

        asort( $selected );

        return $selected;
    }

    public function by_region( $filtered, $cache_key = false ) {
        $selected = [];

        if ( false && cache::has( $cache_key ) ) {
            $selected = Cache::get( $cache_key );
            //clock( $selected );
        } else {
            foreach ( $filtered['regions'] as $region_id ) {
                if ( ! $region_id ) {
                    continue;
                }
                //clock( $region_id );

                $region     = Region::firstWhere( 'id', $region_id );
                $clock_name = $region->name . ' - ';

                $primary_only = true;
                if ( 'alola' === $region->slug || 'galar' === $region->slug ) {
                    $primary_only = false;
                }

                $pokemons = [];
                $title    = [$region->name . ' Region'];

                $varieties = $filtered['varieties'];
                if ( 'alola' === $region->slug ) {
                    //$pokemons = $region->pokemonsCached();

                    $varieties = array_merge( $varieties, [1, 4] );
                } else if ( 'galar' === $region->slug ) {
                    //$pokemons = $region->pokemonsCached();

                    $varieties = array_merge( $varieties, [1, 21] );
                } else if ( 'hisui' === $region->slug ) {
                    //$pokemons = $region->pokemonsCached();

                    //$varieties = array_merge($varieties,  [ 1, 'hisui'] );
                } else if ( empty( $varieties ) ) {
                    $varieties = false;
                }

                if ( ! empty( $varieties ) && [1 => 1] !== $varieties ) {
                    $variety_titles = [];

                    foreach ( $varieties as $variety_id => $value ) {
                        $variety = Variety::firstWhere( 'id', $variety_id );

                        $variety_titles[] = $variety->name;
                    }

                    $title[0] = $title[0] . ' - ' . implode( ', ', $variety_titles );
                }

                $pokemons = $region->pokemonsByVariety( $varieties );

                if ( array_key_exists( 'numbers', $filtered ) ) {
                    $selected[$region_id] = array_merge( $selected, $this->by_pokedex_no( $filtered['numbers'], $region_id, $title, $pokemons ) );
                } else {
                    $title                = Str::title( implode( ', ', $title ) );
                    $selected[$region_id] = [
                        'title'    => $title,
                        'slug'     => Str::slug( $title ),
                        'pokemons' => $pokemons->get(),
                    ];
                }

                if ( $cache_key ) {
                    Cache::rememberForever( $cache_key, function () use ( $selected ) {
                        return $selected;
                    } );
                }
            }

            arsort( $selected );
        }

        return $selected;
    }

    public function clear() {
        $this->filter['regions'] = [];
        $this->filter['numbers'] = [];
        $this->selected          = [];
    }

    public function filter() {
        $this->selected = [[
            'title'    => '',
            'slug'     => '',
            'pokemons' => [],
        ]];

        $filtered = array_map( 'array_filter', $this->filter );
        $filtered = array_filter( $filtered );
        unset( $filtered['method'] );

        $title = '';

        $main_pokemon_columns = ['pokemons.id', 'pokedex_no', 'name', 'slug', 'colour', 'image_slug', 'text_y', 'text_x', 'api_text'];

        $pokemons = [];
        $chosen   = [];
        //clock( $filtered );
        //Pokemon::where( 'pokedex_no', '>=', $start )->select( self::$main_pokemon_columns )->where( 'pokedex_no', '<=', $end )->select( self::$main_pokemon_columns )->with( 'types' );

        $cache_key = [];

        $cache_key['colour'] = 'images>bw';
        if ( $this->update['colour'] ) {
            $cache_key['colour'] = 'images>colour';
        }

        $cache_key['regions'] = array_key_exists( 'regions', $filtered ) ? 'regions>' . implode( '.', array_keys( $filtered['regions'] ) ) : '';
        $cache_key['numbers'] = array_key_exists( 'numbers', $filtered ) ? 'numbers>' . implode( '.', array_keys( $filtered['numbers'] ) ) : '';
        $cache_key['types']   = array_key_exists( 'types', $filtered ) ? 'types>' . implode( '.', array_keys( $filtered['types'] ) ) : '';

        if ( array_key_exists( 'varieties', $filtered ) ) {
            $cache_key['varieties'] = 'varieties>' . implode( '.', array_keys( $filtered['varieties'] ) );
            $filtered['varieties']  = array_map( 'intval', $filtered['varieties'] );

            //if(in_array(2, $filtered['varieties'])) {
            //    $filtered['varieties'][]
            //}
        } else {
            $filtered['varieties'] = [];
        }

        $cache_key = array_filter( $cache_key );
        $cache_key = implode( '_', $cache_key );

        $selected = [];
        if ( array_key_exists( 'regions', $filtered ) ) {
            /*foreach ( $filtered['regions'] as $region_id => $value ) {
            if ( $value ) {
            $chosen['regions'][] = $region_id;
            }
            }*/

            $selected = $this->by_region( $filtered, $cache_key );
            //clock( $regions );
        } else if ( array_key_exists( 'numbers', $filtered ) ) {
            /*foreach ( $filtered['numbers'] as $start => $value ) {
            if ( $value ) {
            $end                 = $start + 199;
            $chosen['numbers'][] = [
            'start' => $start,
            'end'   => $end,
            ];
            }
            }*/

            $chosen['numbers'] = $this->by_pokedex_no( $filtered['numbers'] );
            //clock( $numbers );
        } else if ( array_key_exists( 'varieties', $filtered ) ) {
            foreach ( $filtered['varieties'] as $variety_id ) {
                if ( $variety_id ) {
                    $chosen['varieties'][] = $variety_id;
                    //$this->by_varieties( $varieties )
                }
            }
        } else if ( array_key_exists( 'types', $filtered ) ) {
            foreach ( $filtered['types'] as $type_id ) {
                if ( $type_id ) {
                    $chosen['types'][] = $type_id;
                    //$this->by_varieties( $varieties )
                }
            }
        }
        //clock( $chosen );
        //clock( $pokemons );

        //clock( $selected );

        //if ( count( $chosen_regions ) > 0 ) {
        //$selected = $this->by_region( $chosen_regions, $chosen_numbers );
        //} else if ( count( $chosen_numbers ) > 0 ) {
        //$selected = $this->by_pokedex_no( $chosen_numbers );
        //}

        //clock( $selected['1']['pokemons'] );

        $this->selected = $selected;
    }

    public function mount() {
        $this->regions = Region::orderBy( 'number' )->get();
        $this->types   = Type::orderBy( 'name' )->get();
    }

    // Generate PDF
    public function pdf_save() {
        set_time_limit( 300 ); // Extends to 5 minutes.

        $per_row = 5;

        foreach ( $this->selected as $group ) {
            $timestamp = \Carbon\Carbon::now()->format( 'YmdHs' );
            //$timestamp = '';

            $colour = 'bw';
            if ( $this->update['colour'] ) {
                $colour = 'colour';
            }

            $path     = 'printables/' . $timestamp;
            $filename = $colour . '-' . $group['slug'];

            $data = [
                'selected' => $group,
                'colour'   => $this->update['colour'],
                'per_row'  => $per_row,
            ];

            $pdf = PDF::loadView( 'printables.pokedexPDF', compact( 'data' ) )->setPaper( 'a4', 'landscape' );
            $pdf->save( storage_path( 'app/printables/' . $filename . '-' . $timestamp . '.pdf' ) )->stream( 'pokedex.pdf' );

        }
    }

    public function render() {
        return view( 'livewire.pokedex' )->extends( 'layouts.app' );
    }
}

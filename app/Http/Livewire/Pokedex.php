<?php

namespace App\Http\Livewire;

use App\Models\Pokemon;
use App\Models\Region;
use App\Models\Type;
use App\Services\Download;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Pokedex extends Component {
    public $count = 0;

    public $filter = [
        'regions' => [],
        'numbers' => [],
    ];

    public $regions;

    public $selected = [];

    public $types;

    public $update = [
        'colour' => true,
    ];

    protected $rules = [
        'update.colour'           => 'required|boolean',
        'filter.selected_regions' => 'required|array',
        'filter.selected_numbers' => 'required|array',
    ];

    public function by_pokedex( $chosen_numbers ) {
        $selected = [];

        foreach ( $chosen_numbers as $range ) {
            $start      = $range['start'];
            $end        = $range['end'];
            $end        = $range['start'] + 3;
            $clock_name = $start . ' - ';

            $pokemons = Pokemon::rangedCached( $start, $end );

            $title            = 'Range: ' . $start . ' to ' . $end;
            $selected[$start] = [
                'title'    => $title,
                'slug'     => Str::slug( $title ),
                'pokemons' => $pokemons,
            ];
        }

        asort( $selected );

        return $selected;
    }

    public function by_region( $chosen_regions, $chosen_numbers ) {
        foreach ( $chosen_regions as $region_id ) {
            $region     = Region::firstWhere( 'id', $region_id );
            $clock_name = $region->name . ' - ';

            $primary_only = true;
            if ( 'alola' === $region->slug || 'galar' === $region->slug ) {
                $primary_only = false;
            }

            if ( count( $chosen_numbers ) > 0 ) {
                foreach ( $chosen_numbers as $range ) {
                    $start = $range['start'];
                    $end   = $range['end'];

                    $pokemons = [];

                    if ( $primary_only ) {
                        $pokemons = $region->primaryPokemonsRangedCached( $start, $end );
                    } else {
                        $pokemons = $region->pokemonsRangedCached( $start, $end );
                    }

                    if ( count( $pokemons ) > 0 ) {
                        $title = $region->name . ' Region - From ' . reset( $pokemons )['pokedex_no'] . ' To ' . end( $pokemons )['pokedex_no'];
                    } else {
                        $title = $region->name . ' Region - From ' . $start . ' To ' . $end;
                    }

                    $key = $region_id . '1' . str_pad( $start, 3, '0', STR_PAD_LEFT );

                    $selected[$key] = [
                        'title'    => $title,
                        'slug'     => Str::slug( $title ),
                        'pokemons' => $pokemons,
                    ];
                }
            } else {
                $pokemons = [];
                if ( $primary_only ) {
                    $pokemons = $region->primaryPokemonsCached();
                } else {
                    $pokemons = $region->pokemonsCached();
                }

                $title = $region->name . ' Region';

                $selected[$region_id] = [
                    'title'    => $title,
                    'slug'     => Str::slug( $title ),
                    'pokemons' => $pokemons,
                ];
            }
        }

        arsort( $selected );

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

        $chosen_regions = [];
        foreach ( $this->filter['regions'] as $region_id => $value ) {
            if ( $value ) {
                $chosen_regions[] = $region_id;
            }
        }

        $chosen_numbers = [];
        foreach ( $this->filter['numbers'] as $start => $value ) {
            if ( $value ) {
                $end              = $start + 199;
                $chosen_numbers[] = [
                    'start' => $start,
                    'end'   => $end,
                ];
            }
        }

        $selected = [];
        if ( count( $chosen_regions ) > 0 ) {
            $selected = $this->by_region( $chosen_regions, $chosen_numbers );
        } else if ( count( $chosen_numbers ) > 0 ) {
            $selected = $this->by_pokedex( $chosen_numbers );
        }

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

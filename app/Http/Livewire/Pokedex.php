<?php

namespace App\Http\Livewire;

use App\Models\Pokemon;
use App\Models\Region;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Pokedex extends Component {
    public $current_regions = [];

    public $filter = [
        'selected_regions' => [],
        'colour'           => true,
    ];

    public $regions;

    //public $selected_regions = [];

    protected $rules = [
        'filter.colour.*'         => 'required|boolean',
        'filter.selected_regions' => 'required|array',
    ];

    public function filter() {
        $current_regions = [];
        foreach ( $this->filter['selected_regions'] as $region_id => $value ) {
            if ( $value ) {
                $cache_key = 'region_pokemons_' . $region_id;
                $region    = Region::firstWhere( 'id', $region_id );

                $cache = Cache::get( $cache_key );

                if ( $cache ) {
                    $pokemons = $cache;
                } else {
                    $pokemons = $region->pokemonsCached();

                    Cache::put( $cache_key, $pokemons, 500000 ); //500000 minutes
                }

                $current_regions[$region_id] = [
                    'title'    => 'Region: ' . $region->name,
                    'pokemons' => $pokemons,
                ];
            }
        }

        arsort( $current_regions );
        $this->current_regions = $current_regions;
    }

    public function mount() {
        $this->regions = Region::all();
        //$regions            = [Region::firstWhere( 'id', 1 )];

        /*foreach ( $regions as $region ) {
        $region_id = $region->id;

        $this->pokemons_by_region[$region_id] = [
        'title' => 'Region: ' . $region->name,
        'pokemons' => $region->pokemons,
        ];
        }*/

        //\Debugbar::info( $this->pokemons_by_region );
    }

    public function render() {
        return view( 'livewire.pokedex' )->extends( 'layouts.app' );
    }
}

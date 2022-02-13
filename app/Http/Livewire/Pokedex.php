<?php

namespace App\Http\Livewire;

use App\Models\Pokemon;
use App\Models\Region;
use Livewire\Component;

class Pokedex extends Component {
    public $colour = true;

    public $selected_regions = [];

    public $test = true;

    protected $pokemons_by_region = [];

    public function mount() {
        $pokemons_by_region = [];
        $regions            = Region::all();
        //$regions            = [Region::firstWhere( 'id', 1 )];

        foreach ( $regions as $region ) {
            $region_id = $region->id;
            \Debugbar::info( $region_id );

            $pokemons_by_region[$region_id] = [
                'title' => 'Region: ' . $region->name,
                //'pokemons' => $region->pokemons,
            ];
        }

        \Debugbar::info( $pokemons_by_region );
        $this->pokemons_by_region = arsort( $pokemons_by_region );
    }

    public function render() {
        $pokemons_by_region = [];
        $regions            = Region::all();
        \Debugbar::info( $this->test );

        foreach ( $this->selected_regions as $region_id ) {
            //\Debugbar::info( $region_id );
            $region = Region::firstWhere( 'id', $region_id );

            /*$pokemons = $region->pokemons;

        $pokemons_by_region[$region_id] = [
        'title'    => 'Region: ' . $region->name,
        'pokemons' => $pokemons,
        ];*/
        }

        arsort( $pokemons_by_region );
        //\Debugbar::info( $this->pokemons_by_region );

        return view( 'livewire.pokedex', compact( 'pokemons_by_region', 'regions' ) )->layout( 'pages.pokedex', compact( 'pokemons_by_region', 'regions' ) );
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Pokemon;
use App\Models\Region;
use Livewire\Component;

class Pokedex extends Component {
    public $colour = true;

    public $selected_regions = [];

    public function render() {
        $pokemons_by_region = [];
        $regions            = Region::all();

        \Debugbar::info( $this->selected_regions );
        foreach ( $this->selected_regions as $region_id ) {
            $region = Region::firstWhere( 'id', $region_id );
            \Debugbar::info( $region );
            $pokemons = Pokemon::where( ['region_id' => $region_id] )->get();

        \Debugbar::info( $pokemons );
        $pokemons_by_region[$region_id] = [
        'title'    => 'Region: ' . $region->name,
        'pokemons' => $pokemons,
        ];
        }

        //arsort( $pokemons_by_region );
        //\Debugbar::info( $pokemons_by_region );
        return view( 'livewire.pokedex', compact( 'pokemons_by_region', 'regions' ) )->layout( 'pages.pokedex', compact( 'pokemons_by_region', 'regions' ) );
    }
}

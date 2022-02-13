<div class="jumbotron pokedex"><div class="container">
    <div class="row loading-parent">
        <div class="d-none" wire:loading.class="loading"><div class="spinner-grow"></div></div>
        <div class="filter-sidebar col-2">
            <div>
               <span class="category">Misc</span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="pokedex-colour" value="true" wire:model.defer="filter.colour.value" checked>
                    <label class="form-check-label" for="pokedex-colour">Colour</label>
                </div> 
                <hr class="my-3" />
                <span class="category">Regions</span>
                @foreach($this->regions as $region)
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="pokedex-{{ $region->slug }}" value="{{ $region->id }}" wire:model.defer="filter.selected_regions.{{ $region->id }}">
                        <label class="form-check-label" for="pokedex-{{ $region->slug }}">{{ $region->name }}</label>
                    </div> 
                @endforeach

                <button wire:click="filter" type="submit">Update</button>
            </div>
        </div>
        
        <div class="filter-content col-md-9 offset-md-1">
            @if( count($this->current_regions) > 0 ) 
                @foreach( $this->current_regions as $region )
                    <div class="row {{ $region['title'] }}">
                        <h1 class="@if($filter['colour']) colour @else bw @endif">{{ $region['title'] }}</h1>
                        @foreach($region['pokemons'] as $pokemon)
                            {{ \Debugbar::info($pokemon)}}
                        @endforeach
                    </div>
                @endforeach
            @else
                <h1 class="select">Please select a region</h1>
            @endif
        </div>
    </div>
</div></div>
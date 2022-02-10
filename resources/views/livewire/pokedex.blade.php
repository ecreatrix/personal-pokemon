<div class="row">
    <div class="filter-sidebar d-flex flex-column flex-shrink-0 p-3 bg-light col-2">
        <form>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="pokedex-colour" value="true" wire:model.lazy="colour" checked>
                <label class="form-check-label" for="pokedex-colour">Colour</label>
            </div> 
        </form>
        <hr class="my-3" />
        <form>
            Regions:
            <!-- <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="pokedex-none" value="None" checked  wire:model.lazy="selected_regions" >
                <label class="form-check-label" for="pokedex-none">None</label>
            </label> 
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="pokedex-all" value="All"  wire:model.lazy="selected_regions" >
                <label class="form-check-label" for="pokedex-all">All</label>
            </label> -->
            @foreach($regions as $region)
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="pokedex-{{ $region->slug }}" value="{{ $region->id }}"  wire:model.lazy="selected_regions" >
                <label class="form-check-label" for="pokedex-{{ $region->slug }}">{{ $region->name }}</label>
            </div> 
            @endforeach
        </form>
    </div>
    
        @if( count($selected_regions) > 0 ) 
            @foreach( $pokemons_by_region as $region )
                <div class="row col-md-9 offset-md-1">
                    <h1 class="@if($colour) colour @else bw @endif">{{ $region['title'] }}</h1>
                    @foreach($region['pokemons'] as $pokemon)
                        <div id="{{ $pokemon->slug }}" class="pokemon card @if($pokemon->colour){{  $pokemon->colour  }}@endif">
                            <img class="image card-img-top" src="@if( $colour ){{ \App\Services\Pokemon::images( $pokemon, 'frontColour' ) }}@else{{ \App\Services\Pokemon::images( $pokemon, 'frontBW' ) }}@endif" />
                            <div class="card-body">
                                <div class="card-title">
                                    {{ $pokemon->pokedex_no }} - {{ $pokemon->name }}
                                </div>
                                <div class="types">
                                    @if(is_object(json_decode($pokemon->types)))
                                        @foreach(json_decode($pokemon->types) as $type)
                                            <span class="{{ $type }}">{{ Illuminate\Support\Str::title( $type ) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
            <div class="d-flex align-items-center justify-content-center">
                <h1 class="select">Please select a region</h1>
            </div>
        @endif
    </div></div>
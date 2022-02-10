 <div class="header">
    <div class="top-bar">
        <div class="stage">{{ json_decode($card->subtypes)[0] }}</div>
        <div class="col namebox">
            <div class="name">{{ $card->name }}</div>
            <span class="lv"></span>
        </div>

        <div class="right">
            <span class="health"><span class="subscript">HP</span>{{ $card->hp }}</span>
            <span class="type">
                @if(is_array(json_decode($card->types)))
                    @foreach(json_decode($card->types) as $energy)
                        <img src="{{ $energy_model::images( $energy, 'border' ) }}" />
                    @endforeach
                @endif
            </span>
        </div>
    </div>
</div>

<div class="{{ $card->main_image_class() }}">
    @if( $card->evolves_from )
        <div class="evolves-from">
            <div class="background-image" style="background-image: url({{ $card->evolves_from->images( 'frontColour' ) }});"></div>

            <div class="special"><span>Evolves from</span>: {{ $card->evolves_from->name }}</div>
        </div>
    @endif

    @if( $card->special() )
        <div class="special">{{ $card->special() }}</div>
    @endif

    {{  \Debugbar::info($card->pokemons->toArray()) }}
    <div class="all count-{{ count( $card->pokemons ) }} row">
        @foreach($card->pokemons as $pokemon)
            <div class="image {{ $pokemon->slug }} col-{{ 12/count( $card->pokemons ) }} background-image" style="background-image: url({{ $pokemon->images( 'frontBW' ) }});"></div>
        @endforeach
    </div>

    <div class="specs">
        @if( count($card->pokemons) == 1 )
            {{ $card->pokemons[0]->image_text_extended() }}
        @elseif( count($card->pokemons) >= 1 )
            @foreach($card->pokemons as $pokemon)
                <span class="info">{{ $pokemon->image_text_basic() }}</span>
            @endforeach
        @endif
    </div>
</div>

<div class="middle">
    @if($card->ability)
        <div class="ability">
            <div class="heading">
                <div class="type">{{ $card->ability.type }}</div>
                <div class="name">{{ $card->ability.name }}</div>
            </div>
            <div class="description">{{ $card->ability.text }}</div>
        </div>
    @endif

    <div class="attacks">
        @foreach(json_decode($card->attacks) as $id => $attack)
            <div class="attack {{ $id }}">
                <div class="heading">
                    <div class="energy-cost col-4">
                        @if($attack->convertedEnergyCost > 0)
                            @foreach($attack->cost as $energy)
                                <img src="{{ $energy_model::images( $energy, 'border' ) }}" />
                            @endforeach
                        @endif
                    </div>
                    <span class="col intentional"></span>
                    <span class="name">{{ $attack->name }}</span>
                    <span class="damage">{{ $attack->damage }}</span>
                </div>
                <div class="description">
                    {{ $attack->text }}
                    @if($attack->note)
                        <div class="note">{{ $attack->note }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
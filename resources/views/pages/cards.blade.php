@extends('layouts.app')

@section('title', 'TCG')
@section('slug', 'tcg')

@section('content')
    <div class="jumbotron"><div class="container"><div class="row">
        @foreach($cards as $card)
            <div id={{ $card->id }} class="{{ $card->main_card_class() }}">
                <img class="img-fluid official" src="{{ $card->image_official }}"/>

                <div id="printable-{{ $card->id }}" class="custom download">
                    {{ view( 'partials.printable.pokemon', compact( 'card', 'card_model', 'pokemon_model', 'energy_model' ) ) }}

                    <div class="footer">
                        @if( \App\Helpers\TypeHelper::type_pokemon() )
                            <div class="bottom-bar">
                                <div class="weakness">
                                    @if($card->weaknesses && is_array(json_decode($card->weaknesses)))
                                        <span class="title">Weakness</span>
                                        <span class="types">
                                            @foreach(json_decode($card->weaknesses) as $weakness)
                                                <span class="type">
                                                    <img src="{{ App\Models\Energy::images( $weakness->type, 'blackBg' ) }}" />
                                                    <span>{{ $weakness->value }}</span>
                                                </span>
                                            @endforeach
                                        </span>
                                    @endif
                                </div>
                                <div class="resistance">
                                    @if($card->resistances && is_array(json_decode($card->resistances)))
                                        <span class="title">Resistance</span>
                                        <span class="types">
                                            @foreach(json_decode($card->resistances) as $resistance)
                                                <span class="type">
                                                    <img src="{{ App\Models\Energy::images( $resistance->type, 'blackBg' ) }}" />
                                                    <span>{{ $resistance->value }}</span>
                                                </span>
                                            @endforeach
                                        </span>
                                    @endif
                                </div>
                                <div class="retreat">
                                    @if($card->retreat && is_array(json_decode($card->retreat)))
                                        <span class="title">Retreat</span>
                                        <span class="types">
                                            @foreach(json_decode($card->retreat) as $retreat)
                                                <span class="type">
                                                    <img src="{{ App\Models\Energy::images( $retreat, 'blackBg' ) }}" />
                                                </span>
                                            @endforeach
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="info">{{ $card->text }}</span>
                        @endif
                        <span class="card-number">{{ $card->deck->slug }}-{{ $card->number }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div></div></div>
@stop
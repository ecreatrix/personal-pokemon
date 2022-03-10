@extends('layouts.app')

@section('title', 'Pokedex')
@section('slug', 'pokedex')

@section('content')
    <div class="jumbotron pokedex update"><div class="container">
        <h1 class="heading">Updated:</h1><div class="row">
        @foreach($pokemons as $pokemon)
            <div id="{{ $pokemon->slug }}" class="pokemon"><div class="card">
                <img src="/{{ \App\Services\Naming::pokemon_images( $pokemon, 'front', true, false ) }}" />
                <h4 class="name">
                    @if( $pokemon->region ) {{ $pokemon->region->name }} - @endif
                    {{ $pokemon->pokedex_no }} - {{ $pokemon->name }}
                </h4>
            </div></div>
        @endforeach
    </div></div></div>

@stop
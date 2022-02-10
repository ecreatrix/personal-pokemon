@extends('layouts.app')

@section('title', 'Welcome')
@section('slug', 'home')

@section('content')
    <div class="jumbotron"><div class="container">
        <div class="row bg-gray-800">
            <div class="col-6 p-4 d-flex align-middle">
                <a href="/pokedex" class="mx-auto my-auto d-block text-center o-50-hover"><img src={{ asset('images/pokedex-logo.png') }} /></a>     
            </div>

            <div class="border-start border-gray-200 col-6 p-4 d-flex align-middle">
                <a href="/cards" class="mx-auto my-auto d-block text-center o-50-hover">
                    <img src={{ asset('images/tcg-logo.png') }} />
                </a>
            </div>
        </div>
    </div></div>
@stop

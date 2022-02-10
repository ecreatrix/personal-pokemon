@extends('layouts.app')

@section('title', 'Pokedex')
@section('slug', 'pokedex')

@section('content')
	<div class="jumbotron pokedex"><div class="container">
		<livewire:pokedex /> 
	</div></div>
@stop
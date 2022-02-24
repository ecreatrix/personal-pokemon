<!DOCTYPE html>
<html class="pdf">
<head>
	<!-- Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Lato:wght@100;400;600;700&display=swap" crossorigin="anonymous">

	<!-- Styles -->
	<link href="http://pokemon.test/styles/app.css" rel="stylesheet" crossorigin="anonymous">

	<style>
		@page { margin: 0; }
	</style>
</head>
<?php  
	$data = [
		'selected' => [
			'title' => "Range 1 to 5",
			'slug' => "range-1-to-5",
			'pokemons' => [
				'0' => [
					'id' => 1,
					'pokedex_no' => "001",
					'name' => "Bulbasaur",
					'slug' => "bulbasaur",
					'colour' => "green",
					'image_slug' => "001Bulbasaur",
					'types' => [
						'0' => [
							'id' => 19,
							'number' => 12,
							'slug' => "grass",
							'name' => "Grass",
						],
						'1' => [
							'id' => 20,
							'number' => 4,
							'slug' => "poison",
							'name' => "Poison",
						],
					],
				],
				'1' => [
					'id' => 2,
					'pokedex_no' => "002",
					'name' => "Ivysaur",
					'slug' => "ivysaur",
					'colour' => "green",
					'image_slug' => "002Ivysaur",
					'types' => [
						'0' => [
							'id' => 19,
							'number' => 12,
							'slug' => "grass",
							'name' => "Grass",
						],
						'1' => [
							'id' => 20,
							'number' => 4,
							'slug' => "poison",
							'name' => "Poison",
						],
					],
				],
				'2' => [
					'id' => 3,
					'pokedex_no' => "003",
					'name' => "Venusaur",
					'slug' => "venusaur",
					'colour' => "green",
					'image_slug' => "003Venusaur",
					'types' => [
						'0' => [
							'id' => 19,
							'number' => 12,
							'slug' => "grass",
							'name' => "Grass",
						],
						'1' => [
							'id' => 20,
							'number' => 4,
							'slug' => "poison",
							'name' => "Poison",
						],
					],
				],
				'3' => [
					'id' => 4,
					'pokedex_no' => "003",
					'name' => "Venusaur Mega",
					'slug' => "venusaur-mega",
					'colour' => "green",
					'image_slug' => "003Venusaur_Mega",
					'types' => [
						'0' => [
							'id' => 19,
							'number' => 12,
							'slug' => "grass",
							'name' => "Grass",
						],
						'1' => [
							'id' => 20,
							'number' => 4,
							'slug' => "poison",
							'name' => "Poison",
						],
					],
				],
				'4' => [
					'id' => 5,
					'pokedex_no' => "003",
					'name' => "Venusaur Gmax",
					'slug' => "venusaur-gmax",
					'colour' => "green",
					'image_slug' => "003Venusaur_Gmax",
					'types' => [
						'0' => [
							'id' => 19,
							'number' => 12,
							'slug' => "grass",
							'name' => "Grass",
						],
						'1' => [
							'id' => 20,
							'number' => 4,
							'slug' => "poison",
							'name' => "Poison",

						],
					],
				],
				'5' => [
					'id' => 6,
					'pokedex_no' => "004",
					'name' => "Charmander",
					'slug' => "charmander",
					'colour' => "red",
					'image_slug' => "004Charmander",
					'types' => [
						'0' => [
							'id' => 21,
							'number' => 10,
							'slug' => "fire",
							'name' => "Fire",

						],
					],
				],
				'6' => [
					'id' => 7,
					'pokedex_no' => "005",
					'name' => "Charmeleon",
					'slug' => "charmeleon",
					'colour' => "red",
					'image_slug' => "005Charmeleon",
					'types' => [
						'0' => [
							'id' => 21,
							'number' => 10,
							'slug' => "fire",
							'name' => "Fire",
						],
					],
				],
			],
		],
		'colour' => true
	]; 
?>
<body class="">
	<div class="jumbotron pokedex printable">
		<div class="heading">{{ $data['selected']['title'] }}</div>
		<div class="table @if($data['colour'])colour @else bw @endif">
		
		@if( count($data['selected']['pokemons']) > 0 ) 
			@foreach($data['selected']['pokemons'] as $key => $pokemon)
				@if($key % 5 == 0 ) <div class="table-row"> @endif

				<div id="{{ $pokemon['slug'] }}" class="pokemon">

					<div class="image"><img src="http://pokemon.test/{{ \App\Services\Naming::pokemon_images( $pokemon, 'front', $data['colour'], false ) }}" /></div>

					<div class="body">
						<div class="title">
							No {{ $pokemon['pokedex_no'] }} - {{ $pokemon['name'] }}
						</div>
						<div class="types">
							@foreach($pokemon['types'] as $type)
							<span class="type {{ $type['slug'] }}">{{ $type['name'] }}</span>
							@endforeach
						</div>
					</div>
				</div>

				@if( ($key + 1) % 5 == 0 ) </div> @endif
			@endforeach
		@else 
			<h3 class="heading">No pokemon found within range</h3>
		@endif
	</div></div>
</body>
</html>
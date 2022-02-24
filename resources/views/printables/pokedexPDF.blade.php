<!DOCTYPE html>
<html class="pdf">
    <head>
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Lato:wght@100;400;600;700&display=swap" crossorigin="anonymous">

        <!-- Styles -->
        <link href="http://pokemon.test/styles/app.css" rel="stylesheet" crossorigin="anonymous">

        <style>
			@page { margin: 1rem; }
		</style>
    </head>
    <body class="">
		<div class="jumbotron pokedex printable">
			<div class="heading">{{ $data['selected']['title'] }}</div>

			<div class="table @if($data['colour'])colour @else bw @endif">
			
			@if( count($data['selected']['pokemons']) > 0 ) 
				@foreach($data['selected']['pokemons'] as $key => $pokemon)
					@if($key % 4 == 0 ) <div class="table-row"> @endif
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
					@if( ($key + 1) % 4 == 0 ) </div> @endif
				@endforeach
			@else 
				<h3 class="heading">No pokemon found within range</h3>
			@endif
		</div></div>
	</body>
</html>
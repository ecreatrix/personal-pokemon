<!DOCTYPE html>
<html class="pdf">
    <head>
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Lato:wght@100;400;600;700&display=swap" crossorigin="anonymous">

        <!-- Styles -->
        <link href="http://pokemon.test/styles/app.css" rel="stylesheet" crossorigin="anonymous">
        <link href="http://pokemon.test/styles/printable.css" rel="stylesheet" crossorigin="anonymous">
    </head>
    <body class="pdf">
		<div class="d-table h-100"><div class="d-table-row"><div class="jumbotron pokedex printable d-table-cell">
			<table class="container @if($data['colour'])colour @else bw @endif" align="center" cellpadding="0">
				<thead class="subheading"><td>{{ $data['selected']['title'] }}</td></thead>
				<tr><td><table class="breakable" align="center" cellpadding="0">
					@if( count($data['selected']['pokemons']) > 0 ) 
						@foreach($data['selected']['pokemons'] as $key => $pokemon)
							@if($key % $data['per_row'] == 0 ) <tr class="{{ $key }}"> @endif

							<td id="{{ $pokemon['slug'] }}" class="pokemon">
								<div class="image"><img src="http://pokemon.test/{{ \App\Services\Naming::pokemon_images( $pokemon, 'front', $data['colour'], false ) }}" /></div>

								<div class="body">
									<div class="title">
										No. {{ $pokemon['pokedex_no'] }} - 
										@if($pokemon['name'] === 'Nidoran ♀') 
											Nidoran - Female
										@elseif($pokemon['name'] === 'Nidoran ♂') 
											Nidoran - Male
										@else 
											{{ $pokemon['name'] }}
										@endif
									</div>
									<div class="text my-2">
										@if($pokemon['text_y']) 
											{{ $pokemon['text_y'] }}
										@elseif($pokemon['text_x']) 
											{{ $pokemon['text_x'] }}
										@else
											{{ $pokemon['api_text'] }}
										@endif
									</div>
									<div class="types count-{{ count($pokemon['types']) }}">
										@foreach($pokemon['types'] as $type)
											<span class="type {{ $type['slug'] }}">{{ $type['name'] }}</span>
										@endforeach
										@if( count($pokemon['types']) == 1 )
											<span class="type empty"></span>
										@endif
									</div>
								</div>
							</td>
							@if( ($key + 1) % $data['per_row'] == 0 ) </tr> @endif
						@endforeach
					@else 
						<h3 class="heading">No pokemon found within range</h3>
					@endif
				</table></td></tr>
			</table>
		</div></div>
	</body>
</html>
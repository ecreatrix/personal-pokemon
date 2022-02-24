@if( count($data['selected']['pokemons']) > 0 ) 
	@foreach($data['selected']['pokemons'] as $key => $pokemon)
		@if($key == 0 ) <div class="table-row heading"><span>{{ $data['selected']['title'] }}</span></div> @endif

		@if($key % $data['per_row'] == 0 ) <div class="table-row {{ $key }}"> @endif

		<div id="{{ $pokemon['slug'] }}" class="pokemon d-tablecell @if($data['colour'])colour @else bw @endif">

			<div class="image"><img src="{{ \App\Services\Naming::pokemon_images( $pokemon, 'front', $data['colour'], false ) }}" /></div>

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
		@if( ($key + 1) % $data['per_row'] == 0 ) </div> @endif
	@endforeach
@else 
	<h3 class="heading">No pokemon found within range</h3>
@endif
<div class="jumbotron pokedex"><div class="container @if($update['colour'])colour @else bw @endif">
	<div class="row loading-parent">
		<div class="d-none" wire:loading.class="loading"><div class="spinner-grow"></div></div>
		<div class="filter-sidebar col-2">
			<div class="accordion" id="pokedexFilterAccordion">
			   	<div class="accordion-item">
			   		<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-misc" aria-expanded="true" aria-controls="collapse-filter-misc">
						Misc
					</div>
					<div id="collapse-filter-misc" class="form-check form-switch misc accordion-collapse collapse show" aria-labelledby="heading-filter-misc">
						<input class="form-check-input" type="checkbox" role="switch" id="pokedex-colour" value="$update['colour']" wire:model="update.colour" checked>
						<label class="form-check-label" for="pokedex-colour">Colour</label>
					</div> 
				</div>

		   		<div class="block heading-2">Filters</div>

				<div class="block dashed by-region accordion-item" wire:model="filter.method.region" key="filter-region">
					<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-region" aria-expanded="true" aria-controls="collapse-filter-region">
						By Region
					</div>
					<div id="collapse-filter-region" class="form-check form-switch accordion-collapse collapse show" aria-labelledby="heading-filter-region">
						@foreach($regions as $region)
							<input class="form-check-input" type="checkbox" role="switch" id="pokedex-{{ $region->slug }}" value="{{ $region->id }}" wire:model.defer="filter.regions.{{ $region->id }}" key="filter-region-{{ $region->id }}">
							<label class="form-check-label" for="pokedex-{{ $region->slug }}">{{ $region->name }}</label>
						@endforeach
					</div> 
				</div>

				<div class="block dashed by-number accordion-item" wire:model="filter.method.number" key="filter-number">
					<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-pokedexno" aria-expanded="true" aria-controls="collapse-filter-pokedexno">
						By Pokedex No
					</div>
					<div id="collapse-filter-pokedexno" class="form-check form-switch accordion-collapse collapse show" aria-labelledby="heading-filter-pokedexno">
						@for($i = 1; $i <= 1000; $i = $i + 200 ) 
							<input class="form-check-input" type="checkbox" role="switch" id="pokedex-no-{{ $i }}" value="{{ $i }}" wire:model.defer="filter.numbers.{{ $i }}" key="filter-number-{{ $i }}">
							<label class="form-check-label" for="pokedex-no-{{ $i }}">{{ \App\Services\Naming::pad_pokedex_no($i) }} - {{ \App\Services\Naming::pad_pokedex_no($i + 200 - 1) }}</label>
						@endfor
					</div> 
				</div>

				<div class="block dashed by-variety accordion-item" wire:model="filter.method.variety" key="filter-variety">
					<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-variety" aria-expanded="true" aria-controls="collapse-filter-variety">
						By Variety
					</div>
					<div id="collapse-filter-variety" class="form-check form-switch accordion-collapse collapse show" aria-labelledby="heading-filter-variety">
						<input class="form-check-input" type="checkbox" role="switch" id="pokedex-basic" value="1" wire:model.defer="filter.varieties.1" key="filter-type-1">
						<label class="form-check-label" for="pokedex-basic">Basic</label>

						<input class="form-check-input" type="checkbox" role="switch" id="pokedex-mega" value="2" wire:model.defer="filter.varieties.2" key="filter-type-2">
						<label class="form-check-label" for="pokedex-mega">Mega</label>

						<input class="form-check-input" type="checkbox" role="switch" id="pokedex-gmax" value="3" wire:model.defer="filter.varieties.3" key="filter-type-3">
						<label class="form-check-label" for="pokedex-gmax">GMAX</label>
					</div> 
				</div>

				<div class="block dashed by-type accordion-item" wire:model="filter.method.type" key="filter-type">
					<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-type" aria-expanded="true" aria-controls="collapse-filter-type">
						By Type
					</div>
					<div id="collapse-filter-type" class="form-check form-switch accordion-collapse collapse shows" aria-labelledby="heading-filter-type">
						@foreach($types as $type)
							<input class="form-check-input" type="checkbox" role="switch" id="pokedex-{{ $type->slug }}" value="{{ $type->id }}" wire:model.defer="filter.types.{{ $type->id }}" key="filter-type-{{ $type->id }}">
							<label class="form-check-label" for="pokedex-{{ $type->slug }}">{{ $type->name }}</label>
						@endforeach
					</div> 
				</div>
				<div class="d-flex flex-wrap gap-2 justify-content-center mt-3 block" x-ref="panel">
					<button class="btn btn-primary" wire:click="filter" type="submit">Update</button>
					<button class="btn btn-secondary ms-2" wire:click="clear" type="submit">Clear</button>
						<a wire:click="pdf_save" class="btn btn-gray-800 w-100 export @if( count($selected) == 0 ) d-none @endif">Export to PDF</a>
				</div>
			</div>
		</div>

		<div class="filter-content col-md-9 offset-md-1">
			@if( count($selected) > 0 ) 
				<div class="accordion" id="pokedexAccordion">
					@foreach( $selected as $group )
						<div class="accordion-item">
							<h1 class="accordion-header heading" id="heading-{{ $group['slug'] }}">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group['slug'] }}" aria-expanded="true" aria-controls="collapse-{{ $group['slug'] }}">
									<span>{{ $group['title'] }}</span>
								</button>
							</h1>
							<div id="collapse-{{ $group['slug'] }}" class="{{ $group['slug'] }} accordion-collapse collapse show" aria-labelledby="heading-{{ $group['slug'] }}"><div class="row accordion-body downloadable" data-title="{{ $group['title'] }}" data-slug="{{ $group['slug'] }}">
								@if( count($group['pokemons']) > 0 ) 
									@foreach($group['pokemons'] as $pokemon)
										<div id="{{ $pokemon['slug'] }}" class="pokemon @if($pokemon['colour']){{ $pokemon['colour'] }}@endif"><div class="card">
											<div class="card-image-block">
												<img class="image card-img-top" src="{{ \App\Services\Naming::pokemon_images( $pokemon, 'front', $update['colour'], false ) }}" />
											</div>
											<div class="card-body"><div class="moving-border"></div>
												<div class="card-title">
													No. {{ $pokemon['pokedex_no'] }} - {{ $pokemon['name'] }}
													<div class="text my-2">
														@if($pokemon['text_y']) 
															{{ $pokemon['text_y'] }}
														@elseif($pokemon['text_x']) 
															{{ $pokemon['text_x'] }}
														@else
															{{ $pokemon['api_text'] }}
														@endif
													</div>
												</div>
												<div class="types">
													@foreach($pokemon['types'] as $type)
														<span class="type {{ $type['slug'] }}">{{ $type['name'] }}</span>
													@endforeach
												</div>
											</div>
										</div></div>
									@endforeach
								@else 
									<h3 class="heading">No pokemon found within range</h3>
								@endif
							</div></div>
						</div>
					@endforeach
				</div>
			@else
				<h1 class="heading">Please select a region</h1>
			@endif
		</div>
	</div>
</div></div>
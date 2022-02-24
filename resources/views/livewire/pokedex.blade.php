<div class="jumbotron pokedex"><div class="container @if($filter['colour'])colour @else bw @endif">
	<div class="row loading-parent">
		<div class="d-none" wire:loading.class="loading"><div class="spinner-grow"></div></div>
		<div class="filter-sidebar col-2">
			<div class="accordion" id="pokedexFilterAccordion">
			   	<div class="accordion-item">
			   		<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-misc" aria-expanded="true" aria-controls="collapse-filter-misc">
						Misc
					</div>
					<div id="collapse-filter-misc" class="form-check form-switch misc accordion-collapse collapse show" aria-labelledby="heading-filter-misc">
						<input class="form-check-input" type="checkbox" role="switch" id="pokedex-colour" value="$filter['colour']" wire:model.defer="filter.colour" checked>
						<label class="form-check-label" for="pokedex-colour">Colour</label>
					</div> 
				</div>

				<div class="block by-region accordion-item" wire:model="filter.method.region" key="filter-region">
					<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-region" aria-expanded="true" aria-controls="collapse-filter-region">
						By Region
					</div>
					@foreach($regions as $region)
						<div id="collapse-filter-region" class="form-check form-switch accordion-collapse collapse show" aria-labelledby="heading-filter-region">
							<input class="form-check-input" type="checkbox" role="switch" id="pokedex-{{ $region->slug }}" value="{{ $region->id }}" wire:model.defer="filter.regions.{{ $region->id }}" key="filter-region-{{ $region->id }}">
							<label class="form-check-label" for="pokedex-{{ $region->slug }}">{{ $region->name }}</label>
						</div> 
					@endforeach
				</div>

				<div class="block by-number accordion-item" wire:model="filter.method.number" key="filter-number">
					<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-pokedexno" aria-expanded="true" aria-controls="collapse-filter-pokedexno">
						By Pokedex No
					</div>
					@for($i = 1; $i <= 1000; $i = $i + 200 ) 
						<div id="collapse-filter-pokedexno" class="form-check form-switch accordion-collapse collapse show" aria-labelledby="heading-filter-pokedexno">
							<input class="form-check-input" type="checkbox" role="switch" id="pokedex-no-{{ $i }}" value="{{ $i }}" wire:model.defer="filter.numbers.{{ $i }}" key="filter-number-{{ $i }}">
							<label class="form-check-label" for="pokedex-no-{{ $i }}">{{ $i }} - {{ $i + 200 - 1 }}</label>
						</div> 
					@endfor
				</div>

				<div class="block by-type accordion-item" wire:model="filter.method.type" key="filter-type">
					<div class="category accordion-button" data-bs-toggle="collapse" data-bs-target="#collapse-filter-type" aria-expanded="true" aria-controls="collapse-filter-type">
						By Type
					</div>
					@foreach($types as $type)
						<div id="collapse-filter-type" class="form-check form-switch accordion-collapse collapse" aria-labelledby="heading-filter-type">
							<input class="form-check-input" type="checkbox" role="switch" id="pokedex-{{ $type->slug }}" value="{{ $type->id }}" wire:model.defer="filter.types.{{ $type->id }}" key="filter-type-{{ $type->id }}">
							<label class="form-check-label" for="pokedex-{{ $type->slug }}">{{ $type->name }}</label>
						</div> 
					@endforeach
				</div>
				<div class="d-flex flex-wrap gap-2 justify-content-center mt-3 block" x-ref="panel">
					<button class="btn btn-primary" wire:click="filter" type="submit">Update</button>
					<button class="btn btn-secondary ms-2" wire:click="clear" type="submit">Clear</button>
						<a wire:click="blob_create" class="btn btn-gray-800 w-100 export @if( count($selected) == 0 ) d-none @endif" x-ref="exportHTML">Export to PDF</a>
				</div>
			</div>
		</div>

		<div class="filter-content col-md-9 offset-md-1">
			@if( count($selected) > 0 ) 
				<div class="accordion d-none" id="pokedexAccordion">
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
												<img class="image card-img-top" src="{{ \App\Services\Naming::pokemon_images( $pokemon, 'front',$filter['colour'], false ) }}" />
											</div>
											<div class="card-body"><div class="moving-border"></div>
												<div class="card-title">
													No. {{ $pokemon['pokedex_no'] }} - {{ $pokemon['name'] }}
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
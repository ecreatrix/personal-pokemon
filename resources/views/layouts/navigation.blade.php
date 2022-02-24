<header><nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
	<a class="navbar-brand" href="{{ URL::to('/') }}"><img src = "{{ asset('/images/pokeball-logo.png') }}" /></a>
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav me-auto mb-2 mb-lg-0">
		<li class="nav-item">
			<a class="nav-link {{ Request::is('/') ? 'active' : '' }}" aria-current="page" href="{{ URL::to('/') }}">Home</a>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link {{ Request::is('/pokedex') ? 'active' : '' }}" aria-current="page" href="{{ URL::to('/pokedex') }}">Pokedex</a>
			<ul class="dropdown-menu">
				<li class="nav-item">
					<a class="nav-link {{ Request::is('/pokedex/update') ? 'active' : '' }}" aria-current="page" href="{{ URL::to('/pokedex/update') }}">Update</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ Request::is('/export/pokedex') ? 'active' : '' }}" aria-current="page" href="{{ URL::to('/export/pokedex') }}">Export</a>
				</li>
			</ul>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link {{ Request::is('/cards') ? 'active' : '' }}" href="{{ URL::to('/cards') }}">Cards</a>
			<ul class="dropdown-menu">
				<li class="nav-item">
					<a class="nav-link {{ Request::is('/cards/download') ? 'active' : '' }}" aria-current="page" href="{{ URL::to('/cards/download') }}">Download</a>
				</li>
			</ul>
		</li>
		</ul>
		
	</div>
  </div>
</nav></header>

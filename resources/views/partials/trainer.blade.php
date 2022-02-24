<div class="header">
    <div class="top-bar">
        <div class="row row-1">
            {!! $card['header_border']() !!}
            <div class="col type">Trainer</div>
            <div class="col subtype">{{ $card['subtypes'] }}</div>
        </div>

        <div class="row row-2">
	        <div class="col namebox">
	            <div class="name">{{ $card['name'] }}</div>
	        </div>

	        <div class="col right hp">
	            <span class="health">
                    <span class="subscript">HP</span>
                   	{{ $card['hp'] }}
	            </span>
	        </div>
	    </div>
	</div>
</div>

<div class="{{ $card_model->main_card_class($card) }}">
    <img src="{{ $trainer_model::images( $card['slug'], 'bw' ) }}" />
</div>

<div class="middle">
	<div class="description">
		{{ $card['text'] }}
	</div>
</div>
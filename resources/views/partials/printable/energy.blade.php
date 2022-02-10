<div class="header">
    <div class="top-bar">
        <div class="row row-1">
            {!! $card['header_border']() !!}
            <div class="col type">Energy</div>
            <div class="col subtype">{{ $card['subtypes'] }}</div>
        </div>

        <div class="row row-2">
            <div class="col namebox">
                <div class="name">{{ $energy_model::name($card['name']) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="main-image">
    <div class="image ampharos col-12 background-image" style="background-image: url({{ $energy_model::images( $card['slug'], 'borderBg' ) }});"></div>
</div>

<div class="middle">
    <div class="description">
        {{ $energy_model::description($card['name'], $card['text']) }}
    </div>
</div>
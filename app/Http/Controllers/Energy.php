<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Energy extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function all_images() {
        $types = $this->types();

        $images = [];
        foreach ( $types as $slug ) {
            $images[$slug] = $this->images( $slug );

            $newSlug          = Str::camel( 'double-' . $slug );
            $images[$newSlug] = $this->images( $newSlug );

            $newSlug          = Str::camel( 'triple-' . $slug );
            $images[$newSlug] = $this->images( $newSlug );

            $newSlug          = Str::camel( 'quadruple-' . $slug );
            $images[$newSlug] = $this->images( $newSlug );
        }

        return $images;
    }

    public static function description( $name, $text ) {
        // Remove card name from description
        $text = 'XXXXX' . $text;
        $text = Str::replace( 'XXXXX' . $name, 'This card', $text );
        $text = Str::replace( 'XXXXX', '', $text );
        $text = Str::replace( '. ' . $name, '. This card', $text );
        $text = Str::replace( $name, 'this card', $text );

        // Fix to Canadian spelling
        $text = TextHelper::canadian_spelling( $text );

        // Remove useless text
        $text = Str::replace( 'This card provides Colorless Energy. ', 'This card can produce any single energy type. ', $text );
        $text = Str::replace( 'While in play, this card provides every type of Energy but provides 2 Energy at a time. ', '', $text );
        $text = Str::replace( '(Has no effect other than providing Energy.) ', '', $text );

        return $text;
    }

    public static function images( $slug, $type = false ) {
        $slug = TextHelper::canadian_spelling( $slug );
        $slug = Str::replace( '-energy', '', $slug );
        $slug = Str::slug( $slug, '-' );

        if ( 'dangerous' === $slug ) {
            $slug = 'darkness';
        } else if ( 'flash' === $slug && 'electric' === $slug ) {
            $slug = 'lightning';
        } else if ( 'aqua' === $slug ) {
            $slug = 'water';
        } else if ( 'double-aqua' === $slug ) {
            $slug = 'double-water';
        } else if ( 'magma' === $slug ) {
            $slug = 'fighting';
        } else if ( 'double-magma' === $slug ) {
            $slug = 'double-fighting';
        } else if ( 'call' === $slug || 'crystal' === $slug || 'cyclone' === $slug || 'heal' === $slug || 'holon-energy-ff' === $slug || 'holon-energy-gl' === $slug || 'holon-energy-wp' === $slug || 'recover' === $slug || 'memory' === $slug || 'multi' === $slug || 'react' === $slug || 'recycle' === $slug || 'retro' === $slug || 'sp' === $slug || 'upper' === $slug || 'warp' === $slug ) {
            $slug = 'colourless';
        } else if ( 'bounce' === $slug || 'counter' === $slug ) {
            $slug = 'double-colourless';
        } else if ( 'boost' === $slug ) {
            $slug = 'triple-colourless';
        } else if ( 'Super Boost Energy ◇' === $slug ) {
            $slug = 'quadruple-colourless';
        } else if ( 'charge' === $slug || 'full-heal' === $slug || 'potion' === $slug || 'prism' === $slug || Str::contains( $slug, 'blend' ) || 'δ Rainbow' === $slug || 'delta-species-rainbow' === $slug || 'unit-energy-grassfirewater' === $slug || 'unit-energy-lightningpsychicmetal' === $slug ) {
            $slug = 'rainbow';
        } else if ( 'miracle' === $slug ) {
            $slug = 'double-rainbow';
        }

        $dir = '/images/energy';

        $images = [
            'black'                  => $dir . '/' . $slug . '-black.png',
            'blackBg'                => $dir . '/' . $slug . '-black-bg.png',
            'blackSVG'               => $dir . '/' . $slug . '-black.svg',

            'colourBordered'         => $dir . '/' . $slug . '-colour-border.png',
            'colourBorderedSVG'      => $dir . '/' . $slug . '-colour-border.svg',

            'colour'                 => $dir . '/' . $slug . '-colour.png',
            'colourSVG'              => $dir . '/' . $slug . '-colour.png',

            'border'                 => $dir . '/' . $slug . '-border.png',
            'borderDark'             => $dir . '/' . $slug . '-border-dark.png',
            'borderSVG'              => $dir . '/' . $slug . '-border.svg',

            'colourBorderedBg'       => $dir . '/' . $slug . '-colour-border-bg.png',
            'colourBorderedBgSVG'    => $dir . '/' . $slug . '-colour-border-bg.svg',

            'colourBg'               => $dir . '/' . $slug . '-colour-bg.png',
            'colourBgSVG'            => $dir . '/' . $slug . '-colour-bg.svg',

            'borderBg'               => $dir . '/' . $slug . '-border-bg.png',
            'borderBgSVG'            => $dir . '/' . $slug . '-border-bg.svg',

            'colourBorderedDouble'   => $dir . '/' . $slug . '-colour-border-double.png',
            'colourDouble'           => $dir . '/' . $slug . '-colour-double.png',
            'borderDouble'           => $dir . '/' . $slug . '-border-double.png',
            'colourBorderedBgDouble' => $dir . '/' . $slug . '-colour-border-double-bg.png',
            'colourBgDouble'         => $dir . '/' . $slug . '-colour-double-bg.png',
            'borderBgDouble'         => $dir . '/' . $slug . '-border-double-bg.png',
        ];

        $images['cardImage'] = $images['colourBordered'];
        $images['blob']      = $images['colour'];

        if ( $type && array_key_exists( $type, $images ) ) {
            $image = $images[$type];

            return $image;
        }

        return $images;
    }

    public static function name( $name ) {
        $name = Str::replace( ' Energy', '', $name );
        $name = TextHelper::canadian_spelling( $name );

        return $name;
    }

    public function types() {
        return [
            'darkness',
            'lightning',
            'fighting',
            'fire',
            'grass',
            'metal',
            'psychic',
            'water',
            'colourless',
            'fairy',
            'rainbow',
            'dragon',
        ];
    }
}

<?php
namespace App\Services;

use Illuminate\Support\Str;

class Naming {
    public static function cacheKey( $model, $key ) {
        return sprintf(
            "%s/%s-%s",
            $model->getTable(),
            $model->getKey(),
            $model->updated_at->timestamp
        ) . ':' . $key;
    }

    public static function english_by_key( $entries, $key = 'name' ) {
        foreach ( $entries as $info ) {
            if ( 'en' === $info->language->name ) {
                $text = $info->$key;
                return str_replace( 'POKéMON', 'Pokémon', $text );
            }
        }

        return '';
    }

    public static function generation_no( $generation ) {
        return Str::upper( str_replace( 'generation-', '', $generation ) );
    }

    public static function image_text_basic() {
        $region = current( (Array) json_decode( $this->attributes['regions'] ) );
        $text   = 'No. ' . $this->attributes['pokedex_no'] . ' - ' . $region . ' ' . $this->attributes['genus'];

        return $text;
    }

    public static function image_text_extended() {
        $text = $this->image_text_basic() . ' - HT: ' . $this->attributes['height'] . ' WT: ' . $this->attributes['weight'] . ' lbs.';

        return $text;
    }

    public static function make_pokemon_slug( $name ) {
        $slug = str_replace( 'dark-', '', $name );
        $slug = str_replace( '-vmax', '', $name );
        $slug = str_replace( '-break', '', $name );
        $slug = str_replace( '-d', '', $name );
        $slug = str_replace( '-v', '', $name );

        $slug = str_replace( 'alolan-', '', $name );

        $slug = str_replace( '-gx', '', $name );
        $slug = str_replace( '-ex', '', $name );
        $slug = str_replace( 'm-', '', $name );

        // Remove everything before 's
        $exploded = explode( '\'s ', $slug );
        if ( count( $exploded ) > 1 ) {
            //\Debugbar::info( Str::afterLast( $slug, '\'s ' ) );
            //\Debugbar::info( explode( '\'s ', $slug ) );
            unset( $exploded[0] );

            $slug = implode( ' ', $exploded );
        }

        return Str::slug( $slug );
    }

    public static function pad_pokedex_no( $pokedex_no ) {
        return str_pad( $pokedex_no, 3, '0', STR_PAD_LEFT );
    }

    public static function pokemon_images( $pokemon, $type = false ) {
        $pokedex_no = $pokemon->pokedex_no;

        $dir    = '/images/pokemon';
        $images = [
            'frontColour' => $dir . '/fronts-colour/' . $pokedex_no . '.png',
            'frontBW'     => $dir . '/fronts-bw/' . $pokedex_no . '.png',
            'back'        => $dir . '/backs/' . $pokedex_no . '.png',
            'threeD'      => $dir . '/3ds/' . $pokedex_no . '.png',
            'gif'         => $dir . '/gifs/' . $pokedex_no . '.gif',
        ];

        $images['blob'] = $images['frontColour'];

        if ( 'mysterious-fossil' === $pokedex_no ) {
            $dir    = '/images/trainer';
            $images = [
                'frontColour' => $dir . '/colour/' . $pokedex_no . '.png',
                'frontBW'     => $dir . '/bw/' . $pokedex_no . '.png',
                'back'        => false,
                'threeD'      => false,
                'gif'         => false,
            ];
        }

        if ( $type && array_key_exists( $type, $images ) ) {
            $image = $images[$type];

            return $image;
        }

        return $images;
    }

    public static function url_id( $url, $slug ) {
        $slug   = $slug . '/';
        $length = strlen( $slug );
        $id     = substr( $url, strpos( $url, $slug ) + $length, -1 );

        if ( $id ) {
            return $id;
        }

        return false;
    }
}

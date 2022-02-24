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
                $text = str_replace( 'POKéMON', 'Pokémon', $info->$key );
                $text = str_replace( "\n", ' ', $text );

                return $text;
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

    public static function pad_pokedex_no( $pokedex_no ) {
        return str_pad( $pokedex_no, 3, '0', STR_PAD_LEFT );
    }

    public static function pokemon_images( $pokemon, $type = false, $colour = true, $asset = true ) {
        if ( is_object( $pokemon ) ) {
            $filename = $pokemon->image_slug;
        } else {
            //clock( $pokemon );
            //return;
            $filename = $pokemon['image_slug'];
        }

        $dir    = '/images/pokemon';
        $images = [
            'front'  => [
                'colour' => $dir . '/colour/' . $filename . '.png',
                'bw'     => $dir . '/bw/' . $filename . '.png',
            ],
            'back'   => $dir . '/backs/' . $filename . '.png',
            'threeD' => $dir . '/3ds/' . $filename . '.png',
            'gif'    => $dir . '/gifs/' . $filename . '.gif',
        ];

        $images['blob'] = $images['front']['colour'];

        if ( 'mysterious-fossil' === $filename ) {
            $dir    = '/images/trainer';
            $images = [
                'front'  => [
                    'colour' => $dir . '/colour/' . $filename . '.png',
                    'bw'     => $dir . '/bw/' . $filename . '.png',
                ],
                'back'   => false,
                'threeD' => false,
                'gif'    => false,
            ];
        }

        if ( $type && array_key_exists( $type, $images ) ) {
            $image = $images[$type];

            if ( $colour && array_key_exists( 'colour', $image ) ) {
                $image = $image['colour'];
            } else if ( array_key_exists( 'bw', $image ) ) {
                $image = $image['bw'];
            }

            if ( $asset ) {
                return asset( $image );
            } else {
                return $image;
            }
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

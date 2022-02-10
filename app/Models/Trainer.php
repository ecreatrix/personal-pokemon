<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model {
    use HasFactory;

    protected $fillable = ['slug'];

    public static function images( $slug, $type = false ) {
        $dir = '/images/trainer';

        $images = [
            'colour' => $dir . '/colour/' . $slug . '.png',
            'bw'     => $dir . '/bw/' . $slug . '.png',
        ];
        $images['blob'] = $images['colour'];

        if ( $type && array_key_exists( $type, $images ) ) {
            $image = $images[$type];

            return $image;
        }

        return $images;
    }
}

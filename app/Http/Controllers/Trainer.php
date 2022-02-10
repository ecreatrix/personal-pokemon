<?php

namespace App\Models;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Trainer extends BaseController {
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

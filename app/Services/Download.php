<?php

namespace App\Services;
use App\Models\Card;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use Validator;

class Download {
    public static function clean_blob( $blob ) {
        if ( $blob ) {
            $base64_image = str_replace( 'data:image/png;base64,', '', $blob );
            $base64_image = str_replace( ' ', '+', $base64_image );
            $base64_image = base64_decode( $base64_image );
            //Image::make( $base64_image )->save( $path . '.png' )->destroy();
            return $base64_image;
        }

        return $blob;
    }

    // Create directory if it doesn't exist and return file path/name
    public static function prep( $path, $filename, $refresh = false ) {
        if ( ! $refresh && \File::exists( $path . '/' . $filename ) ) {
            return false;
        }

        // check if $folder is a directory
        if ( ! \File::isDirectory( $path ) ) {
            \File::makeDirectory( $path, 493, true );
        }

        return $path . $filename;
    }
}

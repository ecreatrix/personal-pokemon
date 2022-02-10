<?php

namespace App\Services;
use App\Models\Card;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use Validator;

class DownloadFile {
    public function card_images( Request $request ) {
        $data = $request->all();

        $validator = Validator::make( $data, [
            'id'    => 'required',
            'image' => 'required',
        ] );

        if ( $validator->fails() ) {
            return response( ['error' => $validator->errors(), 'Validation Error'] );
        }

        $id = $request->get( 'id' );

        $card   = Card::findOrFail( $id );
        $set_id = $card->deck->slug;
        $number = $card->number;
        $slug   = $card->slug;

        $path = $this->prep( 'official', $set_id, $number, $slug );
        if ( $path ) {
            $image = $card->image_official;
            Image::make( $image )->save( $path . '.png' )->destroy();
            $card->image_local = $path . '.png';
        }
        \Debugbar::info( $path );

        $path = $this->prep( 'custom', $set_id, $number, $slug, true );
        if ( $path ) {
            $base64_image       = str_replace( 'data:image/png;base64,', '', $request->get( 'image' ) );
            $base64_image       = str_replace( ' ', '+', $base64_image );
            $base64_image       = base64_decode( $base64_image );
            $card->image_custom = $path . '.png';
            Image::make( $base64_image )->save( $path . '.png' )->destroy();
        }
        \Debugbar::info( $path );

        $card->save();

        return response( ['message' => 'Card ' . $id . '\'s Image downloaded successfully'], 201 );
    }

    public function download( $image, $set_id, $id, $name ) {
        $client = new \GuzzleHttp\Client();

        $directory = public_path( '/api/tcg/original/' . $set_id . '/' );
        if ( ! File::isDirectory( $directory ) ) {
            // path does not exist
            File::makeDirectory( $directory, 0777, true, true );
            //\Debugbar::info( $directory );
        }

        $file = $directory . $id . '-' . $name . '.png';
        if ( ! File::exists( $file ) ) {
            $img = Image::make( $image )->save( $file );
            //\Debugbar::info( $img->response() );
        }

    }

    // Create directory if it doesn't exist and return file path/name
    public function prep( $version, $set_id, $id, $slug, $refresh = false ) {
        $path     = base_path( 'printables/' ) . $set_id . '/';
        $filename = $slug . '-' . $set_id . '-' . $id . '-' . $version;

        if ( ! $refresh && \File::exists( $path . '/' . $filename ) ) {
            return false;
        }

        // check if $folder is a directory
        if ( ! \File::isDirectory( $path ) ) {
            // 493 = $mode of mkdir() function that is used file File::makeDirectory (493 is used by default in \File::makeDirectory
            // true -> this says, that folders are created recursively here! Example: you want to create a directory in company_img/username and the folder company_img does not exist. This function will fail without setting the 3rd param to true
            // http://php.net/mkdir  is used by this function

            //\Debugbar::info('dir make: '.$dir );
            \File::makeDirectory( $path, 493, true );
        }

        return $path . $filename;
    }
}

<?php

namespace App\Services\TCG;

use App\Models\Deck;

class Deck {
    public function db( $set ) {
        $slug = $set->id;

        $deck = Deck::firstOrNew( ['slug' => $slug] );

        if ( $deck->wasRecentlyCreated ) {
            $deck->api = json_encode( $set );

            $deck->name         = $set->name;
            $deck->slug         = $slug;
            $deck->series       = $set->series;
            $deck->card_count   = $set->total;
            $deck->release_date = $set->releaseDate;

            $deck->save();
        }

        return $deck;
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecksTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'decks' );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'decks', function ( Blueprint $table ) {
            $table->id();

            $table->string( 'name' );
            $table->string( 'series' );
            $table->string( 'slug' )->unique();

            $table->integer( 'card_count' );
            $table->date( 'release_date' );

            $table->string( 'source' );

            // Entire API call
            $table->json( 'api' );

            $table->timestamps();
        } );
    }
}

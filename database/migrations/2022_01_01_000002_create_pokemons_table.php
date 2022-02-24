<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'pokemons' );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'pokemons', function ( Blueprint $table ) {
            $table->id();

            $table->string( 'name' )->unique();
            $table->string( 'slug' )->unique();
            $table->string( 'pokedex_no' );

            //$table->string( 'supertype' );

            $table->string( 'colour' )->nullable();

            $table->string( 'generation' )->nullable();

            $table->string( 'habitat' )->nullable();

            $table->string( 'text' )->nullable();
            $table->string( 'genus' )->nullable();

            $table->string( 'previous_stage' )->nullable();
            $table->string( 'next_stage' )->nullable();

            $table->string( 'height' )->nullable();
            $table->string( 'weight' )->nullable();

            $table->foreignId( 'variety_id' )
                  ->references( 'id' )->on( 'varieties' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->json( 'description' )->nullable();

            $table->string( 'image_slug' );

            $table->json( 'sprites' )->nullable();

            $table->string( 'source' );

            // Entire API call
            $table->json( 'api' );

            $table->timestamps();
        } );
    }
}

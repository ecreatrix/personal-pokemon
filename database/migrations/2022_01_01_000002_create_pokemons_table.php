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

            $table->string( 'name' );
            $table->string( 'slug' )->unique();
            $table->string( 'pokedex_no' );

            //$table->string( 'supertype' );

            $table->foreignId( 'variety_id' )
                  ->references( 'id' )->on( 'varieties' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->string( 'previous_stage' )->nullable();
            $table->string( 'next_stage' )->nullable();

            $table->string( 'genus' )->nullable();

            $table->string( 'text_y' )->nullable();
            $table->string( 'text_x' )->nullable();
            $table->string( 'api_text' )->nullable();

            $table->boolean( 'genderable' )->nullable();
            //$table->boolean( 'has_varieties' )->nullable();

            $table->integer( 'height' )->nullable();
            $table->integer( 'weight' )->nullable();
            $table->integer( 'hp' )->nullable();
            $table->integer( 'attack' )->nullable();
            $table->integer( 'defense' )->nullable();
            $table->integer( 'special_attack' )->nullable();
            $table->integer( 'special_defense' )->nullable();
            $table->integer( 'speed' )->nullable();
            $table->string( 'colour' )->nullable();

            $table->string( 'generation' )->nullable();

            $table->string( 'habitat' )->nullable();

            $table->integer( 'pokeapi_id' )->nullable();

            $table->string( 'image_slug' );

            $table->string( 'source' );

            $table->json( 'sprites' )->nullable();

            // Entire API call
            $table->json( 'api' );

            $table->timestamps();
        } );
    }
}

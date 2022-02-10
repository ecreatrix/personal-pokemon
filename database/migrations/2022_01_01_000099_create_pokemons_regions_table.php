<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsRegionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'pokemons_regions' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'pokemons_regions', function ( Blueprint $table ) {
            $table->id();

            // Relations
            $table->foreignId( 'pokemon_id' )
                  ->references( 'id' )->on( 'pokemons' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->foreignId( 'region_id' )
                  ->references( 'id' )->on( 'regions' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->unique( ['region_id', 'pokemon_id'] );
            $table->boolean( 'primary' );

            $table->timestamps();
        } );
    }
}

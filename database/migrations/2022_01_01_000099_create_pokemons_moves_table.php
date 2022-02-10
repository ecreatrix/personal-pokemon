<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsMovesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'pokemons_moves' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'pokemons_moves', function ( Blueprint $table ) {
            $table->id();

            // Relations
            $table->foreignId( 'pokemon_id' )
                  ->references( 'id' )->on( 'pokemons' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->foreignId( 'move_id' )
                  ->references( 'id' )->on( 'moves' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->unique( ['move_id', 'pokemon_id'] );

            $table->timestamps();
        } );
    }
}

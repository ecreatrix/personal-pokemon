<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsAbilitiesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'pokemons_abilities' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'pokemons_abilities', function ( Blueprint $table ) {
            $table->id();

            // Relations
            $table->foreignId( 'pokemon_id' )
                  ->references( 'id' )->on( 'pokemons' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->foreignId( 'ability_id' )
                  ->references( 'id' )->on( 'abilities' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->unique( ['ability_id', 'pokemon_id'] );

            $table->timestamps();
        } );
    }
}

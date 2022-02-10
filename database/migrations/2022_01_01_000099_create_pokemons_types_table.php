<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsTypesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'pokemons_types' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'pokemons_types', function ( Blueprint $table ) {
            $table->id();

            // Relations
            $table->foreignId( 'pokemon_id' )
                  ->references( 'id' )->on( 'pokemons' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->foreignId( 'type_id' )
                  ->references( 'id' )->on( 'types' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->unique( ['type_id', 'pokemon_id'] );

            $table->timestamps();
        } );
    }
}

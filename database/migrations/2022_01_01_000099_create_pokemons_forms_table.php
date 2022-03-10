<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsFormsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'pokemons_forms' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'pokemons_forms', function ( Blueprint $table ) {
            $table->id();

            $table->string( 'slug' );
            $table->string( 'name' );

            // Relations
            $table->foreignId( 'pokemon_id' )
                  ->references( 'id' )->on( 'pokemons' )
                  ->onUpdate( 'cascade' )
                  ->onDelete( 'cascade' );

            $table->timestamps();
        } );
    }
}

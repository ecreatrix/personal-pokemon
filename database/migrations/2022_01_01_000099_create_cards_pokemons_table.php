<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsPokemonsTable extends Migration {
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists( 'cards_pokemons' );
  }

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create( 'cards_pokemons', function ( Blueprint $table ) {
      $table->id();

      // Relations
      $table->foreignId( 'pokemon_id' )
            ->references( 'id' )->on( 'pokemons' )
            ->onUpdate( 'cascade' )
            ->onDelete( 'cascade' );

      $table->foreignId( 'card_id' )
            ->references( 'id' )->on( 'cards' )
            ->onUpdate( 'cascade' )
            ->onDelete( 'cascade' );

      $table->unique( ['card_id', 'pokemon_id'] );

      $table->timestamps();
    } );
  }
}

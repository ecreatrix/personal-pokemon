<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration {
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists( 'cards' );
  }

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create( 'cards', function ( Blueprint $table ) {
      $table->id();

      $table->string( 'slug' )->unique();
      $table->unique( ['id', 'deck_id'] );

      // Card info from API
      $table->string( 'name' );

      $table->string( 'supertype' );
      $table->string( 'subtypes' );
      $table->string( 'number' ); // in Deck

      $table->longText( 'text' )->nullable();

      //$table->string( 'description' );

      // Pokemon only
      $table->json( 'types' )->nullable();
      //$table->string('genus');

      $table->integer( 'hp' )->nullable();
      $table->string( 'level' )->nullable();

      $table->json( 'abilities' )->nullable();
      $table->json( 'attacks' )->nullable();
      $table->json( 'resistances' )->nullable();
      $table->json( 'weaknesses' )->nullable();
      $table->json( 'retreat' )->nullable();

      $table->string( 'image_official' );
      $table->string( 'image_local' )->nullable();
      $table->string( 'image_custom' )->nullable();

      $table->string( 'source' );

      // Relations
      /*$table->foreign( 'pokemon_ids' )
      ->references( 'id' )->on( 'pokemons' )
      ->nullable()
      ->onUpdate( 'cascade' )
      ->onDelete( 'cascade' );*/

      $table->foreignId( 'evolves_from_id' )
            ->nullable()
            ->references( 'id' )->on( 'pokemons' );

      $table->foreignId( 'evolves_to_id' )
            ->nullable()
            ->references( 'id' )->on( 'pokemons' );

      $table->foreignId( 'deck_id' )
            ->nullable()
            ->references( 'id' )->on( 'decks' );

      // Entire API call
      $table->json( 'api' );

      $table->timestamps();
    } );
  }
}

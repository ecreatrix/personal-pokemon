<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Cards that the kids have asked for but I have not currated
class CreateCardsUsersTable extends Migration {
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists( 'cards_users' );
  }

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create( 'cards_users', function ( Blueprint $table ) {
      $table->id();

      $table->string( 'slug' )->unique();

      $table->string( 'status' );

      $table->foreignId( 'card_id' )
            ->references( 'id' )->on( 'cards' )
            ->onUpdate( 'cascade' )
            ->onDelete( 'cascade' );

      $table->foreignId( 'user_id' )
            ->references( 'id' )->on( 'users' )
            ->onUpdate( 'cascade' )
            ->onDelete( 'cascade' );

      $table->timestamps();
    } );
  }
}

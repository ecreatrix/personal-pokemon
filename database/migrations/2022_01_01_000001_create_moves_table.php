<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'moves' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'moves', function ( Blueprint $table ) {
            $table->id();

            $table->integer( 'number' );

            $table->string( 'slug' );
            $table->string( 'name' );
            $table->string( 'type' );
            $table->string( 'description' )->nullable();
            $table->string( 'generation' );

            $table->string( 'class' );

            $table->string( 'source' );

            $table->timestamps();
        } );
    }
}

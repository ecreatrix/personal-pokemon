<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExistingTable extends Migration {
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
        Schema::table( 'pokemons', function ( Blueprint $table ) {
            //$table->integer( 'pokeapi_id' )->nullable();
        } );
    }
}

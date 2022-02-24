<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVarietiesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'varieties' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'varieties', function ( Blueprint $table ) {
            $table->id();

            $table->string( 'slug' );
            $table->string( 'name' );

            $table->string( 'source' );

            $table->timestamps();
        } );
    }
}

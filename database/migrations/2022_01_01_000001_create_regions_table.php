<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'regions' );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'regions', function ( Blueprint $table ) {
            $table->id();

            $table->string( 'number' )->unique();
            $table->string( 'slug' )->unique();
            $table->string( 'name' )->unique();
            $table->string( 'generation' )->unique();
            $table->json( 'locations' )->nullable();

            $table->string( 'source' );

            // Entire API call
            $table->json( 'api' );

            $table->timestamps();
        } );
    }
}

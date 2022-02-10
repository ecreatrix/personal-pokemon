<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'types' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'types', function ( Blueprint $table ) {
            $table->id();

            $table->integer( 'number' );

            $table->string( 'slug' );
            $table->string( 'name' );
            $table->string( 'generation' );

            $table->json( 'double_damage_to' );
            $table->json( 'double_damage_from' );

            $table->json( 'half_damage_to' );
            $table->json( 'half_damage_from' );

            $table->json( 'no_damage_to' );
            $table->json( 'no_damage_from' );

            $table->string( 'source' );

            $table->timestamps();
        } );
    }
}

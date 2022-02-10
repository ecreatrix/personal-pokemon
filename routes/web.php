<?php

//use App\Http\Controllers\Cards;
//use App\Http\Controllers\Pokedex;
use App\Http\Controllers\Cards;
use App\Http\Controllers\Pokedex as PokedexController;
use App\Http\Livewire\PokedexLivewire;
use App\Services\DownloadFile;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::group( [], function () {
    Route::match( 'get', '/', function () {
        return view( 'pages/welcome' );
    } );

    Route::match( ['post', 'get', 'put'], '/cards', [Cards::class, 'cards'] )->name( 'All Cards' );

    // Download card images
    Route::match( ['put'], '/cards/download', [DownloadFile::class, 'card_images'] );

    Route::match( ['post', 'get', 'put'], '/pokedex', PokedexLivewire::class )->name( 'Pokedex' );

    Route::match( ['post', 'get', 'put'], '/pokedex/update', [PokedexController::class, 'update'] )->name( 'Pokedex Update' );
} );
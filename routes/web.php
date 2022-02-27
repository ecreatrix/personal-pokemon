<?php

//use App\Http\Controllers\Cards;
//use App\Http\Controllers\Pokedex;
use App\Http\Controllers\Cards;
use App\Http\Controllers\PokedexUpdate;
use App\Http\Livewire\Pokedex as PokedexLivewire;
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
    Route::match( ['get'], '/test', function () {
        $data = [
            'per_row'  => 4,
            'selected' => [
                'title'    => "Range 1 to 5",
                'slug'     => "range-1-to-5",
                'pokemons' => [
                    '0' => [
                        'id'         => 1,
                        'pokedex_no' => "001",
                        'name'       => "Bulbasaur",
                        'slug'       => "bulbasaur",
                        'colour'     => "green",
                        'image_slug' => "001Bulbasaur",
                        'types'      => [
                            '0' => [
                                'id'     => 19,
                                'number' => 12,
                                'slug'   => "grass",
                                'name'   => "Grass",
                            ],
                            '1' => [
                                'id'     => 20,
                                'number' => 4,
                                'slug'   => "poison",
                                'name'   => "Poison",
                            ],
                        ],
                    ],
                    '1' => [
                        'id'         => 2,
                        'pokedex_no' => "002",
                        'name'       => "Ivysaur",
                        'slug'       => "ivysaur",
                        'colour'     => "green",
                        'image_slug' => "002Ivysaur",
                        'types'      => [
                            '0' => [
                                'id'     => 19,
                                'number' => 12,
                                'slug'   => "grass",
                                'name'   => "Grass",
                            ],
                            '1' => [
                                'id'     => 20,
                                'number' => 4,
                                'slug'   => "poison",
                                'name'   => "Poison",
                            ],
                        ],
                    ],
                    '2' => [
                        'id'         => 3,
                        'pokedex_no' => "003",
                        'name'       => "Venusaur",
                        'slug'       => "venusaur",
                        'colour'     => "green",
                        'image_slug' => "003Venusaur",
                        'types'      => [
                            '0' => [
                                'id'     => 19,
                                'number' => 12,
                                'slug'   => "grass",
                                'name'   => "Grass",
                            ],
                            '1' => [
                                'id'     => 20,
                                'number' => 4,
                                'slug'   => "poison",
                                'name'   => "Poison",
                            ],
                        ],
                    ],
                    '3' => [
                        'id'         => 4,
                        'pokedex_no' => "003",
                        'name'       => "Venusaur Mega",
                        'slug'       => "venusaur-mega",
                        'colour'     => "green",
                        'image_slug' => "003Venusaur_Mega",
                        'types'      => [
                            '0' => [
                                'id'     => 19,
                                'number' => 12,
                                'slug'   => "grass",
                                'name'   => "Grass",
                            ],
                            '1' => [
                                'id'     => 20,
                                'number' => 4,
                                'slug'   => "poison",
                                'name'   => "Poison",
                            ],
                        ],
                    ],
                    '4' => [
                        'id'         => 5,
                        'pokedex_no' => "003",
                        'name'       => "Venusaur Gmax",
                        'slug'       => "venusaur-gmax",
                        'colour'     => "green",
                        'image_slug' => "003Venusaur_Gmax",
                        'types'      => [
                            '0' => [
                                'id'     => 19,
                                'number' => 12,
                                'slug'   => "grass",
                                'name'   => "Grass",
                            ],
                            '1' => [
                                'id'     => 20,
                                'number' => 4,
                                'slug'   => "poison",
                                'name'   => "Poison",

                            ],
                        ],
                    ],
                    '5' => [
                        'id'         => 6,
                        'pokedex_no' => "004",
                        'name'       => "Charmander",
                        'slug'       => "charmander",
                        'colour'     => "red",
                        'image_slug' => "004Charmander",
                        'types'      => [
                            '0' => [
                                'id'     => 21,
                                'number' => 10,
                                'slug'   => "fire",
                                'name'   => "Fire",

                            ],
                        ],
                    ],
                    '6' => [
                        'id'         => 7,
                        'pokedex_no' => "005",
                        'name'       => "Charmeleon",
                        'slug'       => "charmeleon",
                        'colour'     => "red",
                        'image_slug' => "005Charmeleon",
                        'types'      => [
                            '0' => [
                                'id'     => 21,
                                'number' => 10,
                                'slug'   => "fire",
                                'name'   => "Fire",
                            ],
                        ],
                    ],
                ],
            ],
            'colour'   => false,
        ];
        return view( 'printables.pokedexPDF', compact( 'data' ) )->render();
    } );

    Route::match( ['get'], '/export/pokedex', function () {
        return view( 'pages.exportPokedex' )->render();
    } );

    Route::match( ['post', 'get', 'put'], '/pokedex', PokedexLivewire::class )->name( 'Pokedex' );

    Route::match( ['post', 'get', 'put'], '/pokedex/update', [PokedexUpdate::class, 'update'] )->name( 'Pokedex Update' );
} );
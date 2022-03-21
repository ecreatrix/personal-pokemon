<?php

//use App\Http\Controllers\Cards;
//use App\Http\Controllers\Pokedex;
use App\Http\Controllers\Cards;
use App\Http\Controllers\PokedexAPIUpdate;
use App\Http\Controllers\PokedexCSVImport;
use App\Http\Controllers\PokedexManualUpdate;
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
                        'text_y'     => "While it is young, it uses the nutrients that are stored in the seed on its back in order to grow.",
                        'text_x'     => "There is a plant seed on its back right from the day this Pokémon is born. The seed slowly grows larger.",
                        'api_text'   => "A strange seed was planted on its back at birth.The plant sprouts and grows with this Pokémon.",
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
                        "text_y"     => "Exposure to sunlight adds to its strength. Sunlight also makes the bud on its back grow larger.",
                        "text_x"     => "When the bulb on its back grows large, it appears to lose the ability to stand on its hind legs.",
                        "api_text"   => "When the bulb on its back grows large, it appearsto lose the ability to stand on its hind legs.",
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
                        "text_y"     => "From the time it is born, a flame burns at the tip of its tail. Its life would end if the flame were to go out.",
                        "text_x"     => "It has a preference for hot things. When it rains, steam is said to spout from the tip of its tail.",
                        "api_text"   => "Obviously prefers hot places. When it rains, steamis said to spout from the tip of its tail.",
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
                        "text_y"     => "From the time it is born, a flame burns at the tip of its tail. Its life would end if the flame were to go out.",
                        "text_x"     => "It has a preference for hot things. When it rains, steam is said to spout from the tip of its tail.",
                        "api_text"   => "Obviously prefers hot places. When it rains, steamis said to spout from the tip of its tail.",
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
                        "text_y"     => "From the time it is born, a flame burns at the tip of its tail. Its life would end if the flame were to go out.",
                        "text_x"     => "It has a preference for hot things. When it rains, steam is said to spout from the tip of its tail.",
                        "api_text"   => "Obviously prefers hot places. When it rains, steamis said to spout from the tip of its tail.",
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
                        "text_y"     => "From the time it is born, a flame burns at the tip of its tail. Its life would end if the flame were to go out.",
                        "text_x"     => "It has a preference for hot things. When it rains, steam is said to spout from the tip of its tail.",
                        "api_text"   => "Obviously prefers hot places. When it rains, steamis said to spout from the tip of its tail.",
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
                        "text_y"     => "From the time it is born, a flame burns at the tip of its tail. Its life would end if the flame were to go out.",
                        "text_x"     => "It has a preference for hot things. When it rains, steam is said to spout from the tip of its tail.",
                        "api_text"   => "Obviously prefers hot places. When it rains, steamis said to spout from the tip of its tail.",
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

    Route::match( ['post', 'get', 'put'], '/pokedex/manual-update', [PokedexManualUpdate::class, 'update'] )->name( 'Pokedex Manual Update' );

    Route::match( ['post', 'get', 'put'], '/pokedex/api-update', [PokedexAPIUpdate::class, 'update'] )->name( 'Pokedex API Update' );

    Route::match( ['post', 'get', 'put'], '/pokedex/csv-update', [PokedexCSVImport::class, 'index'] )->name( 'Pokedex CSV Update' );
} );
<?php

namespace App\Http\Controllers;

use App\Imports\PokedexPokemonImport;
use App\Models\Pokemon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class PokedexCSVImport implements ToModel, WithHeadingRow {
    public function import() {
        Excel::import( new UsersImport(), request()->file( 'file' ) );

        return back();
    }

    public function index() {
        $pokemons = [];
        $file     = public_path( 'misc/scrapped-partial.csv' );
        //request()->file( 'file' )->storeAs( 'reports', $fileName, 'public' );
        //clock( request()->file( 'file' ) );
        $pokemons = Excel::import( new PokedexPokemonImport(), $file );

        //$users = User::get();
        //clock( $import );

        return view( 'pages.pokedex-updateCSV', compact( 'pokemons' ) );
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model( array $row ) {
        /*return new User( [
    'name'     => $row['name'],
    'email'    => $row['email'],
    'password' => Hash::make( $row['password'] ),
    ] );*/
    }
}
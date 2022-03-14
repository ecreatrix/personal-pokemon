<?php

namespace App\Imports;

use App\Models\Pokemon;
use Maatwebsite\Excel\Concerns\ToModel;

class PokedexPokemonImport implements ToModel {
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
        ];
    }

    public function get_name( $name ) {
        $name = str_replace( '’', "'", $name );
        $name = str_replace( '“', '"', $name );
        $name = str_replace( '”', '"', $name );

        return $name;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model( array $row ) {
        //clock( $row );
        $pokedex_no = $row[0];

        $custom_pokedex_nos = ['025', '172', '201', '493', '664', '669', '670', '671', '716', '773', '869'];

        if ( in_array( $pokedex_no, $custom_pokedex_nos ) ) {
            $pokemons = Pokemon::where( ['pokedex_no' => $pokedex_no] )->get();

            $name = $this->get_name( $row[1] );

            $text_y = $row[3];
            $text_x = $row[5];

            foreach ( $pokemons as $pokemon ) {
                if ( $pokemon ) {
                    $pokemon->text_y = $text_y;
                    $pokemon->text_x = $text_x;
                    //clock( $pokemon->toArray() );
                    //clock( $pokemon->text );
                    $pokemon->save();
                } else {
                    clock( 'Not found: ' . $i . ' - name: ' . $name . ', text_y: ' . $text_y );
                }
            }
        } else {
            $i = 1;
            foreach ( $row as $part ) {
                if ( array_key_exists( $i + 4, $row ) && ! is_null( $row[$i + 4] ) && '' != $row[$i + 4] ) {
                    $name = $this->get_name( $row[$i] );

                    $text_y = $row[$i + 2];
                    $text_x = $row[$i + 4];

                    $pokemon         = Pokemon::firstWhere( ['pokedex_no' => $pokedex_no, 'name' => $name] );
                    $totem_pokemon   = Pokemon::firstWhere( ['pokedex_no' => $pokedex_no, 'name' => 'Totem ' . $name] );
                    $starter_pokemon = Pokemon::firstWhere( ['pokedex_no' => $pokedex_no, 'name' => $name . ' Starter'] );
                    if ( $pokemon ) {
                        $pokemon->text_y = $text_y;
                        $pokemon->text_x = $text_x;
                        //clock( $pokemon->toArray() );
                        $pokemon->save();
                    } else {
                        clock( 'Not found: ' . $i . ' - name: ' . $name . ', text_y: ' . $text_y );
                    }

                    if ( $totem_pokemon ) {
                        $totem_pokemon->text_y = $text_y;
                        $totem_pokemon->text_x = $text_x;
                        //clock( $totem_pokemon->toArray() );
                        $totem_pokemon->save();
                    }

                    if ( $starter_pokemon ) {
                        $starter_pokemon->text_y = $text_y;
                        $starter_pokemon->text_x = $text_x;
                        //clock( $starter_pokemon->toArray() );
                        $starter_pokemon->save();
                    }
                    //$pokemon->save();
                }

                $i = $i + 5;
            }
        }

        //clock( $pokemon->toArray() );

        //$pokemon->save();
        // }

        return;
    }

    public function startRow(): int {
        return 1;
    }
}

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

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model( array $row ) {
        clock( $row );
        $pokedex_no = $row[0];

        $i = 1;
        foreach ( $row as $part ) {
            if ( array_key_exists( $i + 4, $row ) && ! is_null( $row[$i + 4] ) && '' != $row[$i + 4] ) {
                $name = $row[$i];
                $name = str_replace( '’', "'", $name );
                $name = str_replace( '“', '"', $name );
                $name = str_replace( '”', '"', $name );

                $text_y = $row[$i + 2];
                $text_x = $row[$i + 4];

                $pokemon = Pokemon::firstWhere( ['pokedex_no' => $pokedex_no, 'name' => $name] );
                if ( $pokemon ) {
                    $pokemon->text_y = $text_y;
                    $pokemon->text_x = $text_x;
                    //clock( $pokemon->toArray() );
                    //clock( $pokemon->text );
                    $pokemon->save();
                } else {
                    clock( 'Not found: ' . $i . ' - name: ' . $name . ', text_y: ' . $text_y );
                }
                //$pokemon->save();
            }

            $i = $i + 5;
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

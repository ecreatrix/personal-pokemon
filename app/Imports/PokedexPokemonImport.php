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
        unset( $row[0] );

        $name = $row[1];
        unset( $row[1] );

        unset( $row[2] );

        $text = $row[3];
        unset( $row[3] );

        unset( $row[4] );
        unset( $row[5] );

        $pokemon = Pokemon::firstWhere( ['pokedex_no' => $pokedex_no, 'name' => $name] );
        if ( $pokemon ) {
            $pokemon->text = $text;
            clock( $pokemon->name . ', text: ' . $pokemon->text );
            //$pokemon->save();
        }

        $i = 6;
        foreach ( $row as $part ) {
            if ( array_key_exists( $i, $row ) && null != $row[$i] ) {
                $name = str_replace( '_y', '', $row[$i] );
                $text = $row[$i + 3];
                clock( $i . ' - name: ' . $name . ', text: ' . $text );

                $pokemon = Pokemon::firstWhere( ['pokedex_no' => $pokedex_no, 'name' => $name] );
                if ( $pokemon ) {
                    $pokemon->text = $text;
                    clock( $pokemon->name );
                    clock( $pokemon->text );
                    //$pokemon->save();
                }
                //$pokemon->save();
            }

            $i += 4;
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

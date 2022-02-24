<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonForm extends Model {
    use HasFactory;

    public $table = "pokemons_forms";

    protected $fillable = ['slug', 'name'];

    public function pokemon() {
        return $this->belongsTo( Pokemon::class, 'pokemon_id' );
    }
}

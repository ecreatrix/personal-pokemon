<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model {
	use HasFactory;

	public $table = "regions";

	protected $fillable = ['name', 'slug', 'number'];

	public function pokemons() {
		return $this->belongsToMany( Pokemon::class, 'pokemons_regions' );
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model {
    use HasFactory;

    protected $fillable = ['slug'];

    /**
     * Get the custom deck that owns the card
     */
    public function custom_deck() {
        return $this->belongsTo( CustomDeck::class );
    }

    /**
     * Get the deck that owns the card
     */
    public function deck() {
        return $this->belongsTo( Deck::class );
    }

    /**
     * Get the single pokemon that has the extra information
     */
    public function evolves_from() {
        return $this->belongsTo( Pokemon::class, 'evolves_from_id', 'id' );
    }

    /**
     * Get the single pokemon that has the extra information
     */
    public function evolves_to() {
        return $this->belongsTo( Pokemon::class, 'evolves_to_id', 'id' );
    }
}

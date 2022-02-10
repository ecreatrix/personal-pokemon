<?php

namespace App\Models;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardUser extends Model {
    use HasFactory;

    public $table = "card_user";

    protected $fillable = ['user_id', 'card_id'];

    public function card() {
        return $this->belongsTo( Card::class, 'card_id', 'id' );
    }

    public function user() {
        return $this->belongsTo( User::class, 'user_id', 'id' );
    }
}

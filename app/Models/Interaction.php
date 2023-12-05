<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\InteractionOccured;

class Interaction extends Model
{
    use HasFactory;

    protected $table = 'interactions';

    protected $fillable = ['user_id', 'label', 'type'];


    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}

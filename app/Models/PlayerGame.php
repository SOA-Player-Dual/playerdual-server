<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerGame extends Model
{
    use HasFactory;
    protected $table = 'PlayerGame';
    protected $primaryKey = ['player', 'game'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [];

    public function game()
    {
        return $this->hasOne(Game::class, 'game', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}

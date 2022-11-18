<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $table = 'Player';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
    ];
    public $timestamps = false;
    protected $fillable = [
        'fee',
        'name',
        'description',
        'status',
        'follower',
        'hiredTime',
        'completeRate',
        'album',
        'devices',
        'dateJoin',
        'avgRate',
        'totalRate',
    ];

    public function playerGame()
    {
        return $this->hasMany(PlayerGame::class, 'player');
    }

    public function getGame()
    {
        return $this->hasManyThrough(Game::class, PlayerGame::class, 'player', 'id', 'id', 'game');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }
}

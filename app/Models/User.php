<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'User';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
    ];
    public $timestamps = false;
    protected $fillable = [
        'username',
        'password',
        'email',
        'gender',
        'nickname',
        'dateOfBirth',
        'language',
        'nation',
        'urlCode',
        'balance',
        'donate',
        'dateJoin',
        'donateTotal',
    ];

    public function playerGame()
    {
        return $this->hasMany(PlayerGame::class, 'player');
    }

    public function getGame()
    {
        return $this->hasManyThrough(Game::class, PlayerGame::class, 'player', 'id', 'id', 'game');
    }

    public function player()
    {
        return $this->hasOne(Player::class, 'id');
    }
}

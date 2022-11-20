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
    protected $hidden = ['email', 'password', 'username'];
    public $timestamps = false;
    protected $fillable = [
        'gender',
        'nickname',
        'dateOfBirth',
        'language',
        'nation',
        'avatar',
        'urlCode',
        'balance',
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

    public function contract()
    {
        //has one contract with status is pending or canceled
        return $this->hasOne(Contract::class, 'player')->where('status', 'Pending')->orWhere('status', 'Processing');
    }
}

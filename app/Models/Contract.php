<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $table = 'Contract';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
    ];
    public $timestamps = false;
    protected $fillable = [
        'user',
        'player',
        'time',
        'fee',
        'status',
        'created_at',
    ];

    public function player()
    {
        return $this->hasOne(Player::class, 'id', 'player')->with('user');
    }
}

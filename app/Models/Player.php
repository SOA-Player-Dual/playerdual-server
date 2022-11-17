<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $table = 'Player';
    protected $primaryKey = 'id';
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
    ];
}

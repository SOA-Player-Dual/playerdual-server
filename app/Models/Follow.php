<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $table = 'Follow';
    protected $primaryKey = ['player_id', 'user_id'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [];
}

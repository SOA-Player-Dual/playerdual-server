<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'Post';
    protected $primaryKey = 'user';
    public $incrementing = false;
    public $timestamps = false;
    protected $casts = [
        'user' => 'string'
    ];
    protected $fillable = [
        'content',
        'media',
        'created_at',
        'updated_at',
        'type',
    ];
}

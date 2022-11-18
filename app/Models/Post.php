<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'Post';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = true;
    protected $casts = [
        'id' => 'string'
    ];
    protected $fillable = [
        'user',
        'content',
        'media',
        'created_at',
        'updated_at',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donate extends Model
{
    use HasFactory;
    protected $table = 'Donate';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    protected $casts = [
        'id' => 'string'
    ];
    protected $fillable = [
        'user',
        'player',
        'money',
        'displayName',
        'message',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = 'Rating';
    protected $primaryKey = ['player', 'user'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'comment',
        'rate',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\User');
    }
}

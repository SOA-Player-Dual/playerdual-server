<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'Transaction';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
    ];
    public $timestamps = false;
    protected $fillable = [
        'user',
        'amount',
        'fee',
        'created_at',
        'updated_at',
    ];
}

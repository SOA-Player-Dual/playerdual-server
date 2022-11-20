<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    protected $table = 'OTP';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'user',
        'otp',
        'expired_at',
        'type'
    ];
}

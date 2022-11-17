<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    protected $table = 'OTP';
    protected $primaryKey = 'user';
    public $timestamps = false;
    protected $fillable = [
        'mail',
        'otp',
        'expired_at',
    ];
}

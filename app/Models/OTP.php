<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    protected $table = 'OTP';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
    ];
    public $timestamps = false;
    protected $fillable = [
        'user',
        'otp',
        'expired_at',
        'type'
    ];

    public function getRoles()
    {
        //get role from user
        return $this->belongsTo(User::class, 'user', 'id')->select('role');
    }
}

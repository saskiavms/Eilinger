<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
    ];

    protected $casts = [
        'ip_address' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

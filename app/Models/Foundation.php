<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foundation extends Model
{
    protected $table = 'foundation';

    protected $fillable = [
        'name',
        'strasse',
        'ort',
        'land',
        'nextCouncilMeeting'
    ];

    protected $casts = [
        'nextCouncilMeeting' => 'date',
    ];
}

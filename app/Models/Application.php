<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public const APPL_STATUS = [
        'not_send' => 'not_send', // Antrag noch nicht eingereicht
        'pending' => 'pending', // Antrag liegt bei Eilinger zur Bearbeitung
        'waiting' => 'waiting', //Antrag liegt wieder beim Benutzer zur Beantwortung der Fragen
        'complete' => 'complete', //Angaben im Antrag vollständig. Wartet auf nächste Stiftungsratssitzung
        'approved' => 'approved',
        'blocked' => 'blocked',
        'finished' => 'finished',
    ];

    public const BEREICH = [
        'Bildung' => 'Bildung',
        'Menschen' => 'Menschen',
        'Tierschutz' => 'Tierschutz',
        'Umwelt' => 'Umwelt',
    ];

    public const FORM = [
        'Stipendium' => 'Stipendium',
        'Darlehen' => 'Darlehen',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'appl_status',
        'bereich',
        'appl_status',
        'form',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getApplStatusContextAttribute()
    {
        return [
            'pending' => 'warning', // Antrag liegt bei Eilinger zur Bearbeitung
            'waiting' => 'info', //Antrag liegt wieder beim Benutzer zur Beantwortung der Fragen
            'complete' => 'dark', //Angaben im Antrag vollständig. Wartet auf nächste Stiftungsratssitzung
            'approved' => 'success',
            'blocked' => 'danger',
        ][$this->appl_status] ?? 'gray';
    }
}

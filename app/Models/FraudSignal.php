<?php

namespace App\Models;

use App\Enums\FraudSignalType;
use Illuminate\Database\Eloquent\Model;

class FraudSignal extends Model
{
    protected $fillable = [
        'type',
        'severity',
        'user_id',
        'related_user_id',
        'application_id',
        'related_application_id',
        'details',
        'reviewed_at',
        'reviewed_by_id',
        'is_false_positive',
    ];

    protected $casts = [
        'type'             => FraudSignalType::class,
        'details'          => 'array',
        'reviewed_at'      => 'datetime',
        'is_false_positive' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id')->withTrashed();
    }

    public function application()
    {
        return $this->belongsTo(Application::class)->withTrashed();
    }

    public function relatedApplication()
    {
        return $this->belongsTo(Application::class, 'related_application_id')->withTrashed();
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereNull('reviewed_at')->where('is_false_positive', false);
    }

    public function scopeHighSeverity($query)
    {
        return $query->where('severity', 'high');
    }
}

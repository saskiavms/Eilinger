<?php

namespace App\Models;

use App\Enums\ApplStatus;
use App\Enums\Bereich;
use App\Enums\Form;
use App\Enums\PayoutPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Application extends Model
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'appl_status',
        'bereich',
        'appl_status',
        'form',
        'reason',
        'is_first',
        'req_amount',
        'calc_amount',
        'currency_id',
        'main_application_id',
        'start_appl',
        'end_appl',
        'payout_plan',
        'submission_date',
    ];

    protected $casts = [
        'req_amount' => 'integer',
        'calc_amount' => 'integer',
        'appl_status' => ApplStatus::class,
        'bereich' => Bereich::class,
        'form' => Form::class,
        'start_appl' => 'date',
        'end_appl' => 'date',
        'payout_plan' => PayoutPlan::class,
        'approval_appl' => 'date',
        'submission_date' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getApplStatusContextAttribute($key)
    {
        return [
            ApplStatus::PENDING->name => 'warning', // Antrag liegt bei Eilinger zur Bearbeitung
            ApplStatus::WAITING->name => 'info', // Antrag liegt wieder beim Benutzer zur Beantwortung der Fragen
            ApplStatus::COMPLETE->name => 'dark', // Angaben im Antrag vollständig. Wartet auf nächste Stiftungsratssitzung
            ApplStatus::APPROVED->name => 'success',
            ApplStatus::BLOCKED->name => 'danger',
            ApplStatus::NOTSEND->name => 'secondary',
        ][$this->appl_status->name] ?? 'gray';
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeLoggedInUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enclosures()
    {
        return $this->hasMany(Enclosure::class, 'application_id');
    }

    public function education()
    {
        return $this->hasOne(Education::class, 'application_id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'application_id');
    }

    public function cost()
    {
        return $this->hasOne(Cost::class, 'application_id');
    }

    public function costDarlehen()
    {
        return $this->hasMany(CostDarlehen::class, 'application_id');
    }

    public function financing()
    {
        return $this->hasOne(Financing::class, 'application_id');
    }

    public function financingOrganisation()
    {
        return $this->hasMany(FinancingOrganisation::class, 'application_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'application_id');
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getLastPaymentDateAttribute()
    {
        return $this->payments()->latest('payment_date')->first()?->payment_date;
    }

    /**
     * Check if the application can be edited by the user
     * Applications cannot be edited once they are approved
     */
    public function isEditable(): bool
    {
        return $this->appl_status !== ApplStatus::APPROVED;
    }
}

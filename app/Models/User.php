<?php

namespace App\Models;

use App\Enums\Bewilligung;
use App\Enums\CivilStatus;
use App\Enums\Salutation;
use App\Enums\Types;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'username',
        'type',
        'salutation',
        'lastname',
        'firstname',
        'birthday',
        'nationality',
        'phone',
        'email',
        'password',
        'name_inst',
        'email_inst',
        'phone_inst',
        'website',
        'mobile',
        'soz_vers_nr',
        'civil_status',
        'status',
        'in_ch_since',
        'granting',
        'is_draft',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'granting' => Bewilligung::class,
        'civil_status' => CivilStatus::class,
        'salutation' => Salutation::class,
        'type' => Types::class,
    ];

    public function address()
    {
        return $this->hasMany(Address::class);
    }

    public function parents()
    {
        return $this->hasMany(Parents::class);
    }


    public function siblings()
    {
        return $this->hasMany(Sibling::class);
    }

    public function lastLogin()
    {
        return $this->hasMany(Login::class)->orderBy('created_at', 'desc');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function sendApplications()
    {
        return $this->hasMany(Application::class)->where('appl_status', '!=', 'notsend');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // OVERRIDE
    /**
     * Send email verification und Send change Password Request
     *
     * @call function
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Generate a new two factor code for the user
     */
    public function generateTwoFactorCode()
    {
        $this->timestamps = false;  // Prevent updated_at from being modified
        $this->two_factor_code = random_int(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    /**
     * Reset the two factor code
     */
    public function resetTwoFactorCode()
    {
        $this->timestamps = false;  // Prevent updated_at from being modified
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
}

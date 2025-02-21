<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->generateOtpCode();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'role_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function generateOtpCode()
    {
        do {
            $randomNumber = mt_rand(100000, 999999);
            $checkOtpCode = OtpCode::where('otp', $randomNumber)->first();
        } while ($checkOtpCode);

        $now = Carbon::now();

        $otp_code = OtpCode::updateOrCreate(
            ['user_id' => $this->id],
            [
                'otp' => $randomNumber,
                'valid_until' => $now->addMinutes(5),
            ]
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function Role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function otpcode()
    {
        return $this->hasOne(OtpCode::class, 'user_id');
    }

    public function historyBorrow()
    {
        return $this->belongsToMany(
            Books::class,
            'borrows',
            'user_id',
            'book_id'
        );
    }
    public function loadBorrow()
    {
        return $this->hasMany(Borrows::class, 'user_id');
    }
}

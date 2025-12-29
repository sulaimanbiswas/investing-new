<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'withdrawal_password',
        'invitation_code',
        'referral_code',
        'referred_by',
        'avatar_path',
        'balance',
        'daily_order_limit',
        'freeze_amount',
        'withdrawal_address',
        'wallet_name',
        'wallet_gateway',
        'is_banned',
        'ban_reason',
        'banned_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the password attribute for authentication.
     * Override to bypass the $hidden array restriction.
     * 
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->attributes['password'] ?? null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
            'balance' => 'decimal:2',
            'freeze_amount' => 'decimal:2',
            'daily_order_limit' => 'integer',
            'banned_at' => 'datetime',
            'status' => 'string',
        ];
    }

    /**
     * Get the user who referred this user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get all users referred by this user.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Generate a unique referral code.
     */
    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Get the user's referral link.
     */
    public function getReferralLinkAttribute(): string
    {
        return route('register', ['ref' => $this->referral_code]);
    }

    /**
     * Get the count of users referred by this user.
     */
    public function getReferralCountAttribute(): int
    {
        return $this->referrals()->count();
    }

    /**
     * Get all deposits for this user.
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get all withdrawals for this user.
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Get all login histories for this user.
     */
    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * Get all user order sets for this user.
     */
    public function userOrderSets()
    {
        return $this->hasMany(UserOrderSet::class);
    }

    /**
     * Get all notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get all transactions for this user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Per-gateway wallet addresses.
     */
    public function walletAddresses()
    {
        return $this->hasMany(UserWalletAddress::class);
    }

    /**
     * Get wallet address for a specific gateway, fallback to default.
     */
    public function getWalletAddressForGateway(int $gatewayId): ?string
    {
        $record = $this->walletAddresses()->where('gateway_id', $gatewayId)->first();
        return $record?->address ?? $this->withdrawal_address;
    }

    /**
     * Set or update wallet address for a specific gateway.
     */
    public function setWalletAddressForGateway(int $gatewayId, string $address): void
    {
        $this->walletAddresses()->updateOrCreate(
            ['gateway_id' => $gatewayId],
            ['address' => $address]
        );
    }
}

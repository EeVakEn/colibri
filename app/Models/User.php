<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar',
        'description',
        'name',
        'email',
        'password',
    ];


    protected $appends = ['balance'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    public function contents(): HasManyThrough
    {
        return $this->hasManyThrough(Content::class, Channel::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->with('channel');
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }


    protected function balance(): Attribute
    {
        return Attribute::get(fn () =>
            $this->transactions()
                ->selectRaw("SUM(CASE WHEN `to_id` = ? THEN amount ELSE -amount END) as balance", [$this->id])
                ->value('balance') ?? 0
        );
    }

    public function transactionsSent(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_id');
    }

    public function transactionsReceived(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_id');
    }

    protected function transactionHistory(): Attribute
    {
        return Attribute::get(fn () =>
        $this->transactions()
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($transaction) {
                $sign = $transaction->to_id == $this->id ? '+' : '-';
                return [
                    'from' => $transaction->from,
                    'to' => $transaction->to,
                    'amount' => $sign . $transaction->amount,
                    'type' => $transaction->type,
                    'note' => $transaction->note,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            })
        );
    }

    public function transactions()
    {
        return Transaction::where('from_id', $this->id)
            ->orWhere('to_id', $this->id);
    }
}

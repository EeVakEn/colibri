<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'deposit';
    const TYPE_TRANSFER = 'transfer';

    const TYPES = [
        self::TYPE_DEPOSIT,
        self::TYPE_WITHDRAW,
        self::TYPE_TRANSFER,
    ];

    protected $fillable = ['from_id', 'to_id', 'amount', 'type', 'note'];

    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}

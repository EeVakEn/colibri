<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    public static function deposit(User $to, float $amount, ?string $note = null): Transaction
    {
        $transaction = Transaction::create([
            'to_id' => $to->id,
            'amount' => $amount,
            'type' => Transaction::TYPE_DEPOSIT,
            'note' => $note,
        ]);
        return $transaction;
    }

    public static function withdraw(User $from, float $amount, ?string $note = null): Transaction
    {
        if ($from->balance < $amount) {
            throw new \Exception('Insufficient funds');
        }

        $transaction = Transaction::create([
            'from_id' => $from->id,
            'amount' => $amount,
            'type' => Transaction::TYPE_WITHDRAW,
            'note' => $note,
        ]);
        return $transaction;
    }

    public static function transfer(User $from, User $to, float $amount, ?string $note = null): Transaction
    {
        if ($from->balance < $amount) {
            throw new \Exception('Insufficient funds');
        }

        $transaction = Transaction::create([
            'from_id' => $from->id,
            'to_id' => $to->id,
            'amount' => $amount,
            'type' => Transaction::TYPE_TRANSFER,
            'note' => $note,
        ]);

        return $transaction;
    }
}

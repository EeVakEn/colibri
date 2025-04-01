<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawRequest;
use App\Services\TransactionService;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function index()
    {
        return Inertia::render('Account/Wallet/Index', [
            'user' => auth()->user()->append('transaction_history'),
        ]);
    }

    public function deposit(DepositRequest $request)
    {
        $user = auth()->user();
        TransactionService::deposit($user, $request->amount, 'Deposit made');

        return redirect()->route('account.wallet.index')->with('message', 'Deposit Successful');
    }

    public function withdraw(WithdrawRequest $request)
    {
        $user = auth()->user();

        if ($user->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient funds']);
        }

        TransactionService::withdraw($user, $request->amount, 'Withdrawal made');

        return redirect()->route('account.wallet.index')->with('message', 'Withdraw Success');
    }
}


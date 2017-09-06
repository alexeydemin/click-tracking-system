<?php

namespace App\Http\Controllers;

use App\DayBalance;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactions;
    protected $totals;

    public function __construct(Request $request)
    {
        $userId = $request->userId;
        $date = $request->date;
        $this->transactions = Transaction::where('user_id', $userId)
            ->whereDate('date', $date)
            ->orderBy('date')
            ->get()
            ->round($userId, $date)
            ->appendBalance($userId, $date)
            ->formatBalance();
        $this->totals = DayBalance::where('user_id', $userId)
            ->whereDate('date', $date)
            ->first();
    }

    public function json()
    {
        return response()->json([
            'transactions' => $this->transactions,
            'totals' => $this->totals ?? ''
        ]);
    }

    public function html()
    {
        return view('transactions', [
            'transactions' => $this->transactions,
            'totals' => $this->totals
        ]);
    }
}

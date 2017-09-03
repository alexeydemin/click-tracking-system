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
        $this->transactions = Transaction::where('user_id', $request->userId)
            ->whereDate('date', $request->date)
            ->orderBy('date')
            ->get();
        $this->totals = DayBalance::where('user_id', $request->userId)
            ->whereDate('date', $request->date)
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

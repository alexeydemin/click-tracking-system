<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactions;

    public function __construct(Request $request)
    {
        $this->transactions = Transaction::where('user_id', $request->userId)
            ->whereDate('date', $request->date)
            ->orderBy('date')
            ->get();
    }

    public function json()
    {
        return response()->json($this->transactions);
    }

    public function html()
    {
        return view('transactions', ['transactions' => $this->transactions]);
    }
}

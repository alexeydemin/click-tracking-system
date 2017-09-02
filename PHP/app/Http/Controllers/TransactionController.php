<?php

namespace App\Http\Controllers;

use App\Transaction;

class TransactionController extends Controller
{
    public function json($userId, $date)
    {
        $transactions = Transaction::where('user_id', $userId)
            ->whereDate('date', $date)
            ->orderBy('date')
            ->get();


        return response()->json($transactions, 200);
    }

    public function html($userId, $date)
    {
        echo "html: $userId, $date";
    }
}

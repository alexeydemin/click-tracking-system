<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction\TransactionCollection;

class TransactionController extends Controller
{
    protected $transactions;
    protected $totals;

    public function __construct(Request $request)
    {
        $this->transactions = TransactionCollection::getFormatted($request->userId, $request->date);
        $this->totals = $this->transactions->getTotalsRow();
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

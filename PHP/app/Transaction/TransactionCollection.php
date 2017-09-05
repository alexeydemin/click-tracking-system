<?php

namespace App\Transaction;

use App\DayBalance;
use Illuminate\Database\Eloquent\Collection;

class TransactionCollection extends Collection
{
    public function appendBalance($userId, $date)
    {
        $previousDaysBalance = (new DayBalance)->getPreviousDaysBalance($userId,  $date);
        $currentDaySum = 0;
        foreach($this as $transaction){
            $transaction->balance = $previousDaysBalance + $currentDaySum + $transaction->amount;
            $currentDaySum += $transaction->amount;
            $transaction->balance = $transaction->formatCurrency($transaction->balance);
        }
    }
}

<?php

namespace App\Transaction;

use App\DayBalance;;
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
        }

        return $this;
    }

    public function round()
    {
        $diff = 0;
        foreach( $this as $transaction){
            $amount = $transaction->amount + $diff;
            $amountRounded = floor($amount/1000)*1000;
            $diff = $amount - $amountRounded;
            $transaction->amount = $amountRounded;
        }

        return $this;
    }

    public function formatBalance()
    {
        $this->each(function($item) {
            $item->balance = $item->formatCurrency($item->balance);
        });

        return $this;
    }
}

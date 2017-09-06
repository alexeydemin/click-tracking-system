<?php

namespace App\Transaction;

use App\DayBalance;;

use App\Totals;
use App\Transaction;
use Carbon\Carbon;
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

    public function round($diff = 0)
    {
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

    function getTotalsRow()
    {
        $row = new Totals();
        $row->transaction_type = $this[0]['transaction_type'];
        $row->amount = $this->pluck('amount')->sum();

        return $row;
    }



    public static function getFormatted($userId, $date)
    {
        $lastBalance = self::getByDate($userId, $date)->last()->balance;

        $diff = $lastBalance - floor($lastBalance/1000)*1000;

        return self::getByDate($userId, $date, $diff)
            ->formatBalance();
    }

    public static function getByDate($userId, $date, $diff = 0)
    {
        return Transaction::where('user_id', $userId)
            ->whereDate('date', $date)
            ->orderBy('date')
            ->get()
            ->round($diff)
            ->appendBalance($userId, $date);
    }
}

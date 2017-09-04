<?php

namespace App;

use App\Traits\AmountOutput;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use AmountOutput;

    protected $fillable = [
        'click_id', 'user_id', 'transaction_type', 'amount', 'date'
    ];

    protected $visible = [
        'debit', 'credit', 'balance', 'time'
    ];

    protected $appends = [
        'debit', 'credit', 'balance', 'time'
    ];

    protected function getTimeAttribute()
    {
        return (new \DateTime($this->date))->format('H:i:s');
    }

    protected function getBalanceAttribute()
    {
        $dateOnly = (new \DateTime($this->date))->format('Y-m-d');
        $dayBalance = (new DayBalance)->getPreviousDaysBalance($this->user_id, $dateOnly);
        $cumulativeBalance = self::whereDate('date', $dateOnly)
            ->where('date', '<=', $this->date)
            ->where('user_id', $this->user_id)
            ->sum('amount');

        return $this->formatCurrency($dayBalance + $cumulativeBalance);
    }

}

<?php

namespace App;

use App\Traits\TransactionType;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use TransactionType;

    protected $fillable = [
        'click_id', 'user_id', 'user_type', 'amount', 'date'
    ];

    protected $visible = [
        'debit', 'credit', 'balance', 'time'
    ];

    protected $appends = [
        'debit', 'credit', 'balance', 'time'
    ];

    protected $dates = [
        'date'
    ];

    protected function getTimeAttribute()
    {
        return $this->date->format('H:i:s');
    }

    protected function getBalanceAttribute()
    {
        $dayBalance = (new DayBalance)->getPreviousDaysBalance($this->user_id, $this->date->format('Y-m-d'));
        $cumulativeBalance = self::whereDate('date', $this->date->format('Y-m-d'))
            ->where('date', '<=', $this->date)
            ->where('user_id', $this->user_id)
            ->sum('amount');

        return $this->formatCurrency($dayBalance + $cumulativeBalance);
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

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

    public function getDebitAttribute()
    {
        return ($this->user_type == 'PUB' ? $this->amount : 0)/1000;
    }

    public function getCreditAttribute()
    {
        return ($this->user_type == 'ADV' ? $this->amount : 0)/1000;
    }

    public function getTimeAttribute()
    {
        return $this->date->format('H:i:s');
    }

    public function getBalanceAttribute()
    {
        $dayBalance = (new DayBalance)->getPreviousDaysBalance($this->user_id, $this->date->format('Y-m-d'));
        $cumulativeBalance = self::whereDate('date', $this->date->format('Y-m-d'))
            ->where('date', '<=', $this->date)
            ->where('user_id', $this->user_id)
            ->sum('amount');

        return ($dayBalance + $cumulativeBalance)/1000;
    }

}

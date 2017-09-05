<?php

namespace App;

use App\Traits\AmountOutput;
use App\Transaction\TransactionCollection;
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
        'debit', 'credit', 'time'
    ];

    protected function getTimeAttribute()
    {
        return (new \DateTime($this->date))->format('H:i:s');
    }

    public function newCollection(array $models = [])
    {
        return new TransactionCollection($models);
    }

}

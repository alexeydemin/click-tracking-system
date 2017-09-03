<?php

namespace App\Transaction;

abstract class TransactionPart
{
    const TRANSACTION_TYPE = '';

    public $click_id, $transaction_type, $date, $user_id, $amount;

    public function __construct($click)
    {
        $this->click_id = $click->id;
        $this->transaction_type = static::TRANSACTION_TYPE;
        $this->date = $click->created_at;
    }
}

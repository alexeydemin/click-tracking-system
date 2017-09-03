<?php

namespace App;

abstract class TransactionPart
{
    const USER_TYPE = '';

    public $click_id, $user_type, $date, $user_id, $amount;

    public function __construct($click)
    {
        $this->click_id = $click->id;
        $this->user_type = static::USER_TYPE;
        $this->date = $click->created_at;
    }
}

<?php

namespace App\Transaction;

class CreditPart extends TransactionPart
{
    const USER_TYPE = 'ADV';

    public function __construct($click)
    {
        parent::__construct($click);
        $this->user_id = $click->folder->user_id;
        $this->amount = $click->folder_cost;
    }
}

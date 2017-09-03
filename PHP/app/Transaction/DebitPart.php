<?php

namespace App\Transaction;

class DebitPart extends TransactionPart
{
    const TRANSACTION_TYPE = 'DEBIT';

    public function __construct($click)
    {
        parent::__construct($click);
        $this->user_id = $click->placement->user_id;
        $this->amount = $click->placement_payout;

    }


}

<?php

namespace App\Traits;

use App\Transaction\CreditPart;
use App\Transaction\DebitPart;

trait AmountOutput
{
    protected function getDebitAttribute()
    {
        return $this->formatCurrency($this->transaction_type == DebitPart::TRANSACTION_TYPE ? $this->amount : 0);
    }

    protected function getCreditAttribute()
    {
        return $this->formatCurrency($this->transaction_type == CreditPart::TRANSACTION_TYPE ? $this->amount : 0);
    }

    protected function formatCurrency($value)
    {
        return sprintf('$%s', number_format($value/1000/100, 2));
    }

}
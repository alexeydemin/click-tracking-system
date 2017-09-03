<?php

namespace App\Traits;

trait AmountOutput
{
    protected function getDebitAttribute()
    {
        return $this->formatCurrency($this->user_type == 'PUB' ? $this->amount : 0);
    }

    protected function getCreditAttribute()
    {
        return $this->formatCurrency($this->user_type == 'ADV' ? $this->amount : 0);
    }

    protected function formatCurrency($value)
    {
        return sprintf('$%s', number_format($value/1000/100, 2));
    }

}
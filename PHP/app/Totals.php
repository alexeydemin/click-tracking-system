<?php

namespace App;

use App\Traits\AmountOutput;
use Illuminate\Database\Eloquent\Model;

class Totals extends Model
{
    use AmountOutput;

    protected $visible = [
        'debit', 'credit',
    ];

    protected $appends = [
        'debit', 'credit',
    ];
}

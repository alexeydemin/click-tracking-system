<?php

namespace App;

use App\Traits\AmountOutput;
use Illuminate\Database\Eloquent\Model;

class DayBalance extends Model
{
    use AmountOutput;

    protected $fillable = [
        'user_id', 'date', 'amount',
    ];

    protected $dates = ['date'];

    protected $visible = [
        'debit', 'credit'
    ];

    protected $appends = [
        'debit', 'credit'
    ];

    public static function incrementAmount(TransactionPart $part)
    {
        $dayBalance = self::firstOrNew([
            'user_id' => $part->user_id,
            'date'    => $part->date->format('Y-m-d'),
        ],  ['amount' => 0]);
        $dayBalance->amount += $part->amount;
        $dayBalance->user_type = $part->user_type;
        $dayBalance->save();
    }

    public function getPreviousDaysBalance($userId, $date)
    {
        return $this->where('user_id', $userId)
            ->whereDate('date', '<', $date)
            ->sum('amount');
    }

}

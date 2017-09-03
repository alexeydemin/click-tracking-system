<?php

namespace App;

use App\Traits\TransactionType;
use Illuminate\Database\Eloquent\Model;

class DayBalance extends Model
{
    use TransactionType;

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

    public function incrementAdvertiserBalance(Click $click)
    {
        $dayBalance = self::firstOrCreate([
                'user_id' => $click->folder->user_id,
                'date' =>$click->created_at->format('Y-m-d'),
            ], [

                'amount' => 0
            ]
        );
        $dayBalance->amount += $click->folder_cost;
        $dayBalance->user_type = 'ADV';
        $dayBalance->save();
    }

    public function incrementPublisherBalance(Click $click)
    {
        $dayBalance = self::firstOrCreate([
                'user_id' => $click->placement->user_id,
                'date' =>$click->created_at->format('Y-m-d'),
            ],  [
                'amount' => 0
            ]
        );
        $dayBalance->amount += $click->placement_payout;
        $dayBalance->user_type = 'PUB';
        $dayBalance->save();
    }

    public function getPreviousDaysBalance($userId, $date)
    {
        return $this->where('user_id', $userId)
            ->whereDate('date', '<', $date)
            ->sum('amount');
    }

}

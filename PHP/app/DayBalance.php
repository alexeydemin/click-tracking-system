<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayBalance extends Model
{
    protected $fillable = [
        'user_id', 'date', 'balance',
    ];

    protected $dates = ['date'];

    public function incrementAdvertiserBalance(Click $click)
    {
        $dayBalance = self::firstOrCreate(
            [
                'user_id' => $click->folder->user_id,
                'date' =>$click->created_at->format('Y-m-d')
            ],
            ['balance' => 0]
        );
        $dayBalance->balance += $click->folder_cost;
        $dayBalance->save();
    }

    public function incrementPublisherBalance(Click $click)
    {
        $dayBalance = self::firstOrCreate(
            [
                'user_id' => $click->placement->user_id,
                'date' =>$click->created_at->format('Y-m-d')
            ],
            ['balance' => 0]
        );
        $dayBalance->balance += $click->placement_payout;
        $dayBalance->save();
    }

    public function getPreviousDaysBalance($userId, $date)
    {
        return $this->where('user_id', $userId)
            ->whereDate('date', '<', $date)
            ->sum('balance');
    }

}

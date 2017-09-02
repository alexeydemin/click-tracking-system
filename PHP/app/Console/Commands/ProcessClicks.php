<?php

namespace App\Console\Commands;

use App\Click;
use App\DayBalance;
use App\Transaction;
use Illuminate\Console\Command;

class ProcessClicks extends Command
{

    protected $signature = 'process:clicks';

    protected $description = 'Save transactions and calculate daily balances';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DayBalance $dayBalance)
    {
        Click::where('id', '>', Transaction::max('click_id') )
            ->chunk(500, function($clicks) use ($dayBalance) {
            foreach($clicks as $click){
                Transaction::create([
                    'click_id' => $click->id,
                    'user_id' => $click->folder->user_id,
                    'user_type' => 'ADV',
                    'amount' => $click->folder_cost,
                    'date' => $click->created_at
                ]);
                Transaction::create([
                    'click_id' => $click->id,
                    'user_id' => $click->placement->user_id,
                    'user_type' => 'PUB',
                    'amount' => $click->placement_payout,
                    'date' => $click->created_at
                ]);

                $dayBalance->incrementAdvertiserBalance($click);
                $dayBalance->incrementPublisherBalance($click);
            }
        });
    }
}

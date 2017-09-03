<?php

namespace App\Console\Commands;

use App\CreditPart;
use App\Click;
use App\DayBalance;
use App\DebitPart;
use App\Transaction;
use Illuminate\Console\Command;

class ProcessClicks extends Command
{
    const CHUNK_SIZE = 500;

    protected $signature = 'process:clicks';

    protected $description = 'Save transactions and calculate daily balances';

    public function handle()
    {
        $lastHandledId = Transaction::max('click_id') ?? 0;
        Click::where('id', '>', $lastHandledId )
            ->chunk(self::CHUNK_SIZE, function($clicks){
            foreach($clicks as $click){
                $debitPart = new DebitPart($click);
                $creditPart = new CreditPart($click);
                Transaction::create((array) $debitPart);
                Transaction::create((array) $creditPart);
                DayBalance::incrementAmount($debitPart);
                DayBalance::incrementAmount($creditPart);
            }
        });
    }
}

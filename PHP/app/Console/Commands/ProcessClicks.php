<?php

namespace App\Console\Commands;

use App\Transaction\CreditPart;
use App\Click;
use App\DayBalance;
use App\Transaction\DebitPart;
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

                DayBalance::incrementAmount($debitPart);
                DayBalance::incrementAmount($creditPart);

                self::addMilliseconds($debitPart);
                self::addMilliseconds($creditPart);

                Transaction::create((array) $debitPart);
                Transaction::create((array) $creditPart);

            }
        });
    }

    protected static function addMilliseconds($transactionPart)
    {
        $transactionPart->date = $transactionPart->date->format('Y-m-d H:i:s') . '.' . mt_rand(0, 999);
    }
}

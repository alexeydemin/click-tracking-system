<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckBalanceBetweenTransactionsTest extends TestCase
{
    public function test()
    {
        $period = new \DatePeriod(
            new \DateTime('2017-07-01'),
            new \DateInterval('P1D'),
            new \DateTime('2017-07-14')
        );

        foreach(range(1, 4) as $userId) {
            foreach ($period as $date) {
                $part = 'credit';
                $date = $date->format('Y-m-d');
                $content = $this->get("/api/transactions/json/$userId/$date")->decodeResponseJson();
                $totalDebitJSON = ltrim($content['totals'][$part], '$');
                if ($totalDebitJSON == 0) {
                    $part = 'debit';
                }

                foreach ($content['transactions'] as $key => $transaction) {
                    if(!isset($content['transactions'][$key-1])){
                        continue;
                    }
                    $debit = ltrim($transaction[$part], '$');
                    $balance = ltrim($transaction['balance'], '$');
                    $balancePrev = ltrim($content['transactions'][$key-1]['balance'], '$');
                    $diff = abs($balancePrev + $debit - $balance);
                    echo "\n[$userId][$date][$key] $debit + $balancePrev = $balance | $diff";
                    $this->assertTrue($diff <= 0.011);
                }

                $this->refreshApplication();

            }
        }

        echo "\n\n";
    }
}

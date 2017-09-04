<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckSumsBetweenDaysTest extends TestCase
{
    public function test()
    {
        $periodObj = new \DatePeriod(
            new \DateTime('2017-07-01'),
            new \DateInterval('P1D'),
            new \DateTime('2017-07-14')
        );
        $period = [];
        foreach($periodObj as $date) { $period[] = $date->format('Y-m-d'); }

        foreach(range(1, 4) as $userId) {
            foreach ($period as $key => $date) {
                if(empty($period[$key+1])){
                    continue;
                }

                $part = 'credit';
                $content = $this->get("/api/transactions/json/$userId/$date")->decodeResponseJson();
                $totalDebitJSON = ltrim($content['totals'][$part], '$');
                if ($totalDebitJSON == 0) {
                    $part = 'debit';
                }

                $balancePrev = ltrim(end($content['transactions'])['balance'], '$');
                $this->refreshApplication();

                $content = $this->get("/api/transactions/json/$userId/{$period[$key+1]}")->decodeResponseJson();
                $debit = ltrim($content['transactions'][0][$part], '$');
                $balance = ltrim($content['transactions'][0]['balance'], '$');

                $diff = abs($balancePrev + $debit - $balance);
                echo "\n[$userId][$date][$key] $debit + $balancePrev = $balance | $diff";
                $this->assertTrue($diff <= 0.011);

                $this->refreshApplication();
            }
        }

    }

}

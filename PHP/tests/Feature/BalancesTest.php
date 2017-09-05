<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckBalanceBetweenTransactionsTest extends TestCase
{
    public function testCheckSumsInsideDay()
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
                $totalDebitJSON = ltrim($content['totals'][$part], '$');
                $totalDebitCalculated = 0;
                foreach ($content['transactions'] as $transaction) {
                    $totalDebitCalculated += ltrim($transaction[$part], '$');
                }

                $observationalError = abs($totalDebitJSON - $totalDebitCalculated) / $totalDebitCalculated * 100;
                echo "\n[$userId][$date][$totalDebitJSON - $totalDebitCalculated] -- " . round($observationalError, 3) . '%';
                $this->assertTrue($observationalError < 2);
                $this->refreshApplication();
            }
        }
    }

    public function testCheckSumsBetweenDays()
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

    public function testCheckBalanceBetweenTransactions()
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

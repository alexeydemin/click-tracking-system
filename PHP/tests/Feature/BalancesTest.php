<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BalancesTest extends TestCase
{
    protected $period, $userIds, $part, $content;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->period = $this->createDateRange('2017-07-01', '2017-07-14');
        $this->userIds = range(1, 4);
    }

    public function testCheckSumsInsideDay()
    {

        foreach($this->userIds as $userId) {
            foreach ($this->period as $date) {
                $this->detectTransactionsType($userId, $date);
                $totalDebitJSON = rmSign($this->content['totals'][$this->part]);
                $totalDebitCalculated = 0;
                foreach ($this->content['transactions'] as $transaction) {
                    $totalDebitCalculated += rmSign($transaction[$this->part]);
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
        foreach($this->userIds as $userId) {
            foreach ($this->period as $key => $date) {
                if(empty($this->period[$key+1])){
                    continue;
                }
                $this->detectTransactionsType($userId, $date);
                $balancePrev = rmSign(end($this->content['transactions'])['balance']);
                $this->content = $this->get("/api/transactions/json/$userId/{$this->period[$key+1]}")->decodeResponseJson();
                $debit = rmSign($this->content['transactions'][0][$this->part]);
                $balance = rmSign($this->content['transactions'][0]['balance']);

                $diff = abs($balancePrev + $debit - $balance);
                echo "\n[$userId][$date][$key] $debit + $balancePrev = $balance | $diff";
                $this->assertTrue($diff <= 0.011);

                $this->refreshApplication();
            }
        }
    }

    public function testCheckBalanceBetweenTransactions()
    {

        foreach($this->userIds as $userId) {
            foreach ($this->period as $date) {
                $this->detectTransactionsType($userId, $date);
                foreach ($this->content['transactions'] as $key => $transaction) {
                    if(!isset($this->content['transactions'][$key-1])){
                        continue;
                    }
                    $debit = rmSign($transaction[$this->part]);
                    $balance = rmSign($transaction['balance']);
                    $balancePrev = rmSign($this->content['transactions'][$key-1]['balance']);
                    $diff = abs($balancePrev + $debit - $balance);
                    echo "\n[$userId][$date][$key] $debit + $balancePrev = $balance | $diff";
                    $this->assertTrue($diff <= 0.011);
                }

                $this->refreshApplication();

            }
        }

        echo "\n\n";
    }

    protected function createDateRange($startDate, $endDate, $format = "Y-m-d")
    {
        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);

        $interval = new \DateInterval('P1D'); // 1 Day
        $dateRange = new \DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format($format);
        }

        return $range;
    }

    protected function detectTransactionsType($userId, $date)
    {
        $this->part = 'credit';
        $this->content = $this->get("/api/transactions/json/$userId/$date")->decodeResponseJson();
        $totalDebitJSON = rmSign($this->content['totals'][$this->part]);
        if ($totalDebitJSON == 0) {
            $this->part = 'debit';
        }
        $this->content = $this->get("/api/transactions/json/$userId/$date")->decodeResponseJson();
        $this->refreshApplication();
    }



}

function rmSign($v)
{
    return ltrim($v, '$');
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BalancesTest extends TestCase
{
    const ALMOST_ZERO = 0.00001;

    protected $period, $userIds, $part, $content;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->period = $this->createDateRange('2017-07-01', '2017-07-14');
        $this->userIds = range(1, 4);
    }

    public function testCheckBalanceBetweenTransactions()
    {
        echo "\n______________________1____________________";
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
                    $diff = round(abs($balancePrev + $debit - $balance), 5);
                    $passed = ($diff <= self::ALMOST_ZERO);
                    $key = sprintf("%2s", $key);
                    $debit = sprintf("%4s", $debit);
                    $balancePrev = sprintf("%6s", $balancePrev);
                    $balance = sprintf("%6s", $balance);
                    echo "\n[$userId][$date][$key] $debit + $balancePrev = $balance | $diff" . ( !$passed ? '     [!]' : '');

                    $this->assertTrue( $passed );
                }

                $this->refreshApplication();

            }
        }
    }



    public function testCheckSumsInsideDay()
    {
        echo "\n______________________2____________________";
        foreach($this->userIds as $userId) {
            foreach ($this->period as $date) {
                $this->detectTransactionsType($userId, $date);
                $totalDebitJSON = rmSign($this->content['totals'][$this->part]);
                $totalDebitCalculated = 0;
                foreach ($this->content['transactions'] as $transaction) {
                    $totalDebitCalculated += rmSign($transaction[$this->part]);
                }


                $error = abs($totalDebitJSON - $totalDebitCalculated);
                $passed = $error <= self::ALMOST_ZERO;
                echo "\n[$userId][$date][$totalDebitJSON - $totalDebitCalculated] -- " . round($error, 5)  . ( !$passed ? '     [!]' : '');
                $this->assertTrue($passed);
                $this->refreshApplication();
            }
        }
    }

    public function testCheckSumsBetweenDays()
    {
        echo "\n______________________3____________________";
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

                $diff = round(abs($balancePrev + $debit - $balance), 10);

                $key = sprintf("%2s", $key);
                $debit = sprintf("%4s", $debit);
                $balancePrev = sprintf("%6s", $balancePrev);
                $balance = sprintf("%6s", $balance);

                echo "\n[$userId][$date][$key] $debit + $balancePrev = $balance | $diff";
                $this->assertTrue($diff <= 0.01);

                $this->refreshApplication();
            }
        }
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

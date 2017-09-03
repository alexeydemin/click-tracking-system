<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckSumsInsideDayTest extends TestCase
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
}

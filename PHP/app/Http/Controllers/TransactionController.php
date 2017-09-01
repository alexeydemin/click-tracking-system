<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function json($userId, $date)
    {
        echo "json: $userId, $date";
    }

    public function html($userId, $date)
    {
        echo "html: $userId, $date";
    }
}

<?php

namespace App\Services;

use App\Models\Loan;

class FileService
{
    public static function saveLoan(Loan $loan)
    {
        $file = fopen(__DIR__ . '/../../logs/loan_applications.log', 'a');
        fwrite($file, json_encode((array) $loan) . PHP_EOL);
        fclose($file);
    }
}
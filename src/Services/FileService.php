<?php

namespace App\Services;

use App\Models\Loan;

class FileService
{
    public static function getAllLoans()
    {
        $filePath = __DIR__ . '/../../logs/loan_applications.log';
        if (!file_exists($filePath)) {
            return [];
        }

        $loans = [];
        $file = fopen($filePath, 'r');
        while (($line = fgets($file)) !== false) {
            $loans[] = json_decode($line, true);
        }
        fclose($file);

        return $loans;
    }
    
    public static function saveLoan(Loan $loan)
    {
        $file = fopen(__DIR__ . '/../../logs/loan_applications.log', 'a');
        fwrite($file, json_encode((array) $loan) . PHP_EOL);
        fclose($file);
    }
}
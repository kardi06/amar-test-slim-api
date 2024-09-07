<?php

namespace App\Validators;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class LoanValidator {
    
    public static function validate(array $data):array
    {
        $errors = [];

        // Name validation
        if (!v::stringType()->notEmpty()->regex('/^[A-Za-z]+ [A-Za-z]+\w/')->validate($data['name'])) {
            $errors['name'] = 'Name must be not empty and include at least two names (first and last).';
        }

        // KTP validation
        if (!v::stringType()->length(16, 16)->regex('/^\d{16}$/')->callback(function ($ktp) use ($data) {
            return self::validateKTP($ktp, $data['sex'], $data['date_of_birth']);
        })->validate($data['ktp'])) {
            $errors['ktp'] = 'KTP format is wrong.';
        }

        // Loan amount validation
        if (!v::intType()->min(1000)->max(10000)->validate(intval($data['loan_amount']))) {
            $errors['loan_amount'] = 'Loan amount must be between 1000 and 10000.';
        }

        // Loan purpose validation
        // $validPurposes = ['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment'];
        // if (!in_array(strtolower($data['loan_purpose']), $validPurposes)) {
        if (!v::stringType()->regex('/(vacation|renovation|electronics|wedding|rent|car|investment)/')->validate($data['loan_purpose'])) {
            $errors['loan_purpose'] = 'Purpose must include one of the valid purposes.';
        }

        // Date of Birth Validation
        if (!v::date('d-m-Y')->validate($data['date_of_birth'])) {
            $errors['date_of_birth'] = 'Date of Birth must use format DD-MM-YYYY.';
        }

        // Sex Validation
        if (!v::in(['male', 'female'])->validate(strtolower($data['sex']))) {
            $errors['sex'] = 'Sex must be either male or female.';
        }

        return $errors;

    }

    private static function validateKTP($ktp, $sex, $date_of_birth)
    {
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date_of_birth)) {
            // Extract day, month, and year from date of birth
            list($day, $month, $year) = explode('-', $date_of_birth);

            // Convert to integers to ensure correct formatting
            $day = (int)$day;
            $month = (int)$month;
            $yearShort = substr($year, -2);  // Get last two digits of the year

            // Adjust the day for females by adding 40 to the day
            if (strtolower($sex) === 'female') {
                $day += 40;
            }

            // Ensure the day is formatted as two digits
            $formattedDay = str_pad($day, 2, '0', STR_PAD_LEFT);

            // Format the expected middle KTP part
            $expectedKTPMiddle = sprintf('%02d%02d%s', $formattedDay, $month, $yearShort);
            // Extract the middle portion of the provided KTP
            $ktpMiddle = substr($ktp, 6, 6);

            // Compare the middle part of the provided KTP to the expected format
            // var_dump($expectedKTPMiddle, $ktpMiddle);
            return $ktpMiddle === $expectedKTPMiddle;
        }else{
            return false;
        }
        
    }
}
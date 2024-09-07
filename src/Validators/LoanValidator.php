<?php

namespace App\Validators;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class LoanValidator {
    
    public static function validate(array $data):array
    {
        $errors = [];

        // Name validation
        if (!v::stringType()->notEmpty()->regex('/^[A-Za-z]+ [A-Za-z]+$/')->validate($data['name'])) {
            $errors['name'] = 'Name must be not empty and include at least two names (first and last).';
        }

        // KTP validation
        // if (!v::stringType()->length(16, 16)->regex('/^[0-9]{16}$/')->validate($data['ktp'])) {
        //     $errors['ktp'] = 'Invalid KTP format.';
        // }

        // Loan amount validation
        if (!v::intType()->min(1000)->max(10000)->validate($data['loan_amount'])) {
            $errors['loan_amount'] = 'Loan amount must be between 1000 and 10000.';
        }

        // Loan purpose validation
        // $validPurposes = ['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment'];
        // if (!in_array(strtolower($data['loan_purpose']), $validPurposes)) {
        if (!v::stringType()->regex('/(vacation|renovation|electronics|wedding|rent|car|investment)/')->validate($data['loan_purpose'])) {
            $errors['loan_purpose'] = 'Purpose must include one of the valid purposes.';
        }

        if (!v::date('d-m-Y')->validate($data['date_of_birth'])) {
            $errors['date_of_birth'] = 'Date of Birth must use format DD-MM-YYYY.';
        }

        if (!v::in(['male', 'female'])->validate(strtolower($data['sex']))) {
            $errors['sex'] = 'Sex must be either male or female.';
        }

        if (!v::stringType()->length(16, 16)->regex('/^\d{16}$/')->callback(function ($ktp) use ($data) {
            return self::validateKTP($data['ktp'], $data['sex'], $data['date_of_birth']);
        })->validate($data['ktp'])) {
            $errors['ktp'] = 'KTP format is wrong.';
        }

        return $errors;
        // $nameValidator = v::stringType()->notEmpty()->regex('/^[\w\s]+$/')->contains(' ')
        //                 ->setTemplate('Name field should include at least two names(first and lastname).');

        // $ktpValidator = v::callback(function ($ktp) use ($data) {
        //     return self::validateKTP($ktp, $data['sex'], $data['date_of_birth']);
        // })->setTemplate('Invalid KTP format.');

        // $amountValidator = v::number()->min(1000)->max(10000)->setTemplate('Loan amount must be between 1000 and 10000.');
        // $purposeValidator = v::stringType()->regex('/(vacation|renovation|electronics|wedding|rent|car|investment)/');
        // $dateValidator = v::date('Y-m-d');
        // $sexValidator = v::in(['male', 'female']);

        // // return $nameValidator->validate($data['name']) &&
        // //     $ktpValidator->validate($data['ktp']) &&
        // //     $amountValidator->validate($data['loan_amount']) &&
        // //     $purposeValidator->validate($data['loan_purpose']) &&
        // //     $dateValidator->validate($data['date_of_birth']) &&
        // //     $sexValidator->validate($data['sex']);

        // try {
        //     $nameValidator->assert($data['name']);
        //     $ktpValidator->assert($data['ktp']);
        //     $amountValidator->assert($data['loan_amount']);
        //     $purposeValidator->assert($data['loan_purpose']);
        //     $dateValidator->assert($data['date_of_birth']);
        //     $sexValidator->assert($data['sex']);
        // } catch (NestedValidationException $e) {
        //     // $errors = $e->getMessages(); // Returns an array of custom messages
        //     // throw new NestedValidationException($errors);
        //     // throw $e;
        //     $errors = $e;
        //     // Throw a new exception with formatted errors
        //     throw new \Exception(json_encode(['errors' => $errors]));
        // }
    }

    private static function validateKTP($ktp, $sex, $date_of_birth)
    {
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
        // dd($expectedKTPMiddle);
        // Extract the middle portion of the provided KTP
        $ktpMiddle = substr($ktp, 6, 6);

        // Compare the middle part of the provided KTP to the expected format
         // Debugging: Dump the values you're interested in
        // var_dump($expectedKTPMiddle, $ktpMiddle);
        return $ktpMiddle === $expectedKTPMiddle;
        // $ktpDob = sprintf('%02d%02d%02d', $day, substr($ktp, 4, 2), substr($ktp, 2, 2));
        // $dob = date('dmY', strtotime($date_of_birth));
        // return $ktpDob === substr($dob, 0, 6);
    }
}
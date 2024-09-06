<?php

namespace App\Models;

class Loan
{
    public $name;
    public $ktp;
    public $loan_amount;
    public $loan_period;
    public $loan_purpose;
    public $date_of_birth;
    public $sex;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->ktp = $data['ktp'];
        $this->loan_amount = $data['loan_amount'];
        $this->loan_period = $data['loan_period'];
        $this->loan_purpose = $data['loan_purpose'];
        $this->date_of_birth = $data['date_of_birth'];
        $this->sex = $data['sex'];
    }
}
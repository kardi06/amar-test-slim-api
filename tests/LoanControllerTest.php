<?php

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use App\Controllers\LoanController;

class LoanControllerTest extends TestCase
{
    public function testApplyLoanSuccess()
    {
        $data = [
            'name' => 'John Doe',
            'ktp' => '1112220606983344',
            'loan_amount' => 5000,
            'loan_period' => 12,
            'loan_purpose' => 'vacation',
            'date_of_birth' => '06-06-1998',
            'sex' => 'male',
        ];

        $request = (new ServerRequestFactory())->createServerRequest('POST', '/api/loan')
            ->withParsedBody($data);
        $response = new Response();

        $controller = new LoanController();
        $response = $controller->applyLoan($request, $response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testValidationError()
    {
        $data = [
            'name' => 'John',
            'ktp' => '1234567890123456',
            'loan_amount' => 5000,
            'loan_period' => 12,
            'loan_purpose' => 'unknown',
            'date_of_birth' => '06-06-1998',
            'sex' => 'male',
        ];

        $request = (new ServerRequestFactory())->createServerRequest('POST', '/api/loan')
            ->withParsedBody($data);
        $response = new Response();

        $controller = new LoanController();
        $response = $controller->applyLoan($request, $response);

        $this->assertEquals(422, $response->getStatusCode());
    }
}

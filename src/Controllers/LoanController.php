<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Loan;
use Respect\Validation\Exceptions\ValidationException;
use App\Services\FileService;
use App\Validators\LoanValidator;

class LoanController {
    public function getLoans(Request $request, Response $response)
    {
        // Retrieve all loans from the file
        $loans = FileService::getAllLoans();

        if (empty($loans)) {
            $response->getBody()->write(json_encode(['message' => 'No loan applications found.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($loans));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    
    public function applyLoan(Request $request, Response $response) 
    {
        $data = $request->getParsedBody();
        
        $validator = new LoanValidator;
        $errors = $validator->validate($data);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        }

        $loan = new Loan($data);
        FileService::saveLoan($loan);
        $response->getBody()->write(json_encode(['message' => 'Aplikasi Loan telah disimpan']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        // try{
            
        // }catch (ValidationException $e) {
        //     $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
        //     return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        // }
    }
}
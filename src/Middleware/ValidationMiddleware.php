<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use App\Validators\LoanValidator;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Response as SlimResponse;

class ValidationMiddleware
{
    public function __invoke(Request $request, Handler $handler): Response
    {
        $data = $request->getParsedBody();       

        try {
            LoanValidator::validate($data);
        } catch (ValidationException $e) {
            $response = new SlimResponse();
            $errorResponse = [
                'errors' => $e->getMessages() // Get the custom error messages
            ];
            $response->getBody()->write(json_encode($errorResponse));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }
}
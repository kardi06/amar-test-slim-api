<?php

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\BodyParsingMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use App\Middleware\ValidationMiddleware;
use App\Controllers\LoanController;


require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();
// $app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->get('/', function (Request $request, Response $response){
    $response->getBody()->write('<h2><b>Hai, Silakan Baca Readme Untuk menjalankan aplikasi</b></h2>');

    return $response;   
});

$app->get('/api/loan', LoanController::class . ':getLoans');
$app->post('/api/loan', LoanController::class . ':applyLoan');

$app->run();
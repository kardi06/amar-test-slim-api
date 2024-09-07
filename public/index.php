<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use App\Controllers\LoanController;


require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response){
    $response->getBody()->write('<h2><b>Hai, Silakan Baca Readme Untuk menjalankan aplikasi</b></h2>');

    return $response;   
});

$app->get('/api/loan', LoanController::class . ':getLoans');
$app->post('/api/loan', LoanController::class . ':applyLoan');

$app->run();
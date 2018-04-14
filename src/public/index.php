<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$container['errorHandler'] = function ($container) {
    return new CustomHandler();
};

$container['view'] = new \Slim\Views\PhpRenderer('../templates/');

$app->post('/order/new', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $orderId = filter_var($data['orderId'], FILTER_SANITIZE_STRING);

    $orderProcessingObj = new OrderProcessing($orderId);
    $orderProcessingObj->doOrderProcessing($response);

    $result = "success";
    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($result));
});




$app->run();
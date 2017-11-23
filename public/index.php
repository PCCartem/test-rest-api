<?php
require '../vendor/autoload.php';

$app = new \Slim\Slim();
$cUser = new \App\User();
//Решил логи оставить

$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->get('/', function () use ($app) {
    echo "This is User-Api (Test Task)";
});

$app->post('/user', function () use ($app, $cUser) {
    $response = $cUser->addUser($app->request->post());
    echoRespnse(200, $response);
});

$app->get('/user/:id', function ($id) use ($app, $cUser) {
    $response = $cUser->getUser($id);
    echoRespnse(200, $response);
});

$app->put('/user/:id', function ($id) use ($app, $cUser) {
    $response = $cUser->updateUser($id, $app->request->post());
    echoRespnse(200, $response);
});

$app->delete('/user/:id', function ($id) use ($app, $cUser) {
    $response = $cUser->removeUser($id);
    echoRespnse(200, $response);
});

// Run app
$app->run();

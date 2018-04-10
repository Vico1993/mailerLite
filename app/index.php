<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

// Config Slim App
$config = [
    'displayErrorDetails'       => true,
    'addContentLengthHeader'    => false,
    'db' => [
        'host'      => 'db', 
        'port'      => '3306', 
        'user'      => 'mailerlite', 
        'pass'      => 'mailerlite', 
        'dbname'    => 'mailerlite', 
    ],
];

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/', function (Request $request, Response $response, array $args) {
    $subscriberMapper = new SubscriberMapper($this->db); 

    echo "<pre>";
    var_dump($subscriberMapper->getAllSubscribers());
    echo "</pre>";
});

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->run();

?>
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

// Connection to the Database
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

/**
 * Adding Routes to our application
 */
require __DIR__ . '/routes/SubscriberRoutes.php';
require __DIR__ . '/routes/FieldRoutes.php';

$app->get('/', function (Request $request, Response $response, array $args) {
    echo "Racine début";
    
    // $subscriberMapper = new Model\SubscriberMapper($this->db); 

    // echo "<pre>";
    // var_dump($subscriberMapper->getSubscriberById(1));
    // echo "</pre>";
});

$app->run();

?>
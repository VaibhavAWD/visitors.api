<?php

require '../libs/vendor/autoload.php';
require_once '../include/DbConnect.php';

$app = new Slim\App();

$app->get('/hello/{name}', function ($request, $response, $args) {
    $name = $args['name'];
    $message = "Hello, " . $name . "!";
    return $response->write($message);
});

$app->post('/conncheck', function ($request, $response, $args) {
    $db = new DbConnect();
    $conn = $db->connect();
    if ($conn != null) {
        $message = "Database connection established successfully!";
        return $response->write($message);
    }
});

$app->run();

?>
<?php

require '../libs/vendor/autoload.php';
require_once '../include/util/Helper.php';
require_once '../include/db/DbConnect.php';
require_once '../include/db/DbOperations.php';
require_once '../include/controller/UserController.php';

$app = new Slim\App();

$message = array();

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

/* ------------------------------------------- USERS ------------------------------------------------------- */

$app->post('/register', \UserController::class . ':register');

/* ------------------------------------------- USERS ------------------------------------------------------- */

$app->run();

?>
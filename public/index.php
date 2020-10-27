<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once '../core/Application.php';
require_once '../controllers/SiteController.php';

use app\controllers\SiteController;
use app\core\Application;

// Create a new instance of Application
$app = new Application(dirname(__DIR__));

// register some routes (route, page)
// ** for second param can pass either a string (which will look for a view with the string name) **
// ** or a callback function can be passed which will return some html **
$app->router->get('/', 'home');
$app->router->get('/contact', [SiteController::class, 'contact']);

$app->router->post('/contact', [SiteController::class, 'handleContact']);

// run the application to return the appropriate page
$app->run();

?>
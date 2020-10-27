<?php
namespace app\controllers;

use app\core\Application;

require_once '../core/Application.php';

class SiteController
{

    public function contact() 
    {
        return Application::$app->router->renderView('contact');
    }

    public function handleContact()
    {
        $str = "Handling contact form data";
        print_r($_POST);
        return $str;
    }
}


?>
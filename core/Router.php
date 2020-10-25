<?php

namespace app\core;

require_once 'Request.php';

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function resolve()
    {
        // Get the data needed to determine if a page can be displayed
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        // if a route is not configured for this request page
        // return a 404 page
        if($callback === false)
        {
            return $this->renderView("404");
        }
        // render the returned content on the page

        // if the callback function is not registered then look for a view with the pathname
        if(is_string($callback))
        {
            return $this->renderView($callback);
        }
        // otherwise return the result of the executed callback
        return call_user_func($callback);        
    }

    public function renderView($view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);
        
    }

    protected function layoutContent()
    {
        ob_start();
        include_once Application::$ROOT_DIR."\\views\\layouts\\main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view)
    {
        ob_start();
        include_once Application::$ROOT_DIR."\\views\\$view.php";
        return ob_get_clean();
    }
}


?>
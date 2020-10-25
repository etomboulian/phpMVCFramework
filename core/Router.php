<?php

namespace app\core;

//require_once 'Request.php';

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

        // Intercept requests to api and send them to the apiHandler
        if(substr($path, 0,4) == '/api')
        {
            return $this->renderAPIResponse($method, $path);
        }

        // if a route is not configured for this request page
        // return a 404 page
        if($callback === false)
        {
            return $this->renderView("404");
        }

        // if the callback function is not registered then look for a view with the same name as request path
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

    public function renderAPIResponse($method, $path)
    {
        // return json_encode($apiHandler->getAPIData($method, $path));
        return json_encode(
            Array(
                "message" => "API Not Implemented Yet",
                "method" => $method,
                "path" => $path,
            )
        );
    }

    protected function layoutContent()
    {
        ob_start();
        include_once Application::$app::$ROOT_DIR."\\views\\layouts\\main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view)
    {
        ob_start();
        include_once Application::$app::$ROOT_DIR."\\views\\$view.php";
        return ob_get_clean();
    }
}


?>
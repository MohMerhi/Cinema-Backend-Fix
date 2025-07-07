<?php

function routes()
{
    global $mysqli;

    $base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); //   /article-server
    $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //    /article-server/index


    if (strpos($request, $base_dir) === 0) {
        $request = substr($request, strlen($base_dir));
    }


    if ($request == '') {
        $request = '/';
    }



    $apis = [
        '/movies' => ['controller' => 'MovieController', 'method' => 'HandleMovieGetAndPost'],
        '/movies/update' => ['controller' => 'MovieController', 'method' => 'updateMovie'],
        '/movies/delete' => ['controller' => 'MovieController', 'method' => 'deleteMovie']

    ];


    if (isset($apis[$request])) {
        $controller_name = $apis[$request]['controller'];
        $method = $apis[$request]['method'];
        require_once "controllers/{$controller_name}.php";

        $controller = new $controller_name();
        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            echo "Error: Method {$method} not found in {$controller_name}.";
        }
    } else {
        //echo "404 Not Found";
    }

}
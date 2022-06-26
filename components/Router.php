<?php

class Router {

    private $routes;

    public function __construct() {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

// Return type

    private function getURI() {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run() {
        $uri = $this->getURI();

        /* getting url without .html */
        if (strpos($uri, ".html")) {
            $uri_exp = explode(".html", $uri);
            $uri = $uri_exp[0];
        }

        foreach ($this->routes as $uriPattern => $path) {
            // echo '    $uri - ' . $uri . '<br>';
            if (preg_match("~$uriPattern~", $uri)) {
                //      echo '$uriPattern - ' . $uriPattern . '   $uri - ' . $uri . '    $path - ' . $path . '<br>';
                /* if wrong uri param in controller section, then sends to home */
                if (!empty($uriPattern))
                    $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                else
                    $internalRoute = $path;
                //    echo '$internalRoute - ' . $internalRoute . '<br>';
                $segments = explode('/', $internalRoute);
                   // print_r($segments);
                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst((array_shift($segments)));
                
                $i=0;
                foreach ($segments as $value) {
                    $i++;
                    ${'param'.$i} = $value;
                    //echo '</br> param' .$i .'-'.${'param'.$i};  
                } 
                
                if (!isset($param1)) $param1 = '';
                   
               /* $parameters = array_shift($segments);
                    print_r($segments); 
                echo '$controllerName - ' . $controllerName . '   $actionName - ' . $actionName . '    $parameters - ' . $parameters ;
                /* if wrong uri param in paramiters(lang) section, then sends to home */
                if ((!in_array($actionName, ['actionReview','actionSlider', 'actionRequest', 'actionEsasy', 'actionSurat', 'actionTazelik', 'actionText', 'actionUlanyjy'])) && (!in_array($param1, ['tm', 'ru', 'en', 'tr'])))
                    $param1 = 'tm';
                //    echo '$controllerName - ' . $controllerName . '<br>';
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                $controllerObject = new $controllerName;
                
                switch ($i) {
                    case 0: 
                        $result = $controllerObject->$actionName();
                        break;
                    case 1: 
                        $result = $controllerObject->$actionName($param1);
                        break;
                    case 2: 
                        $result = $controllerObject->$actionName($param1, $param2);
                        break;
                    case 3: 
                        $result = $controllerObject->$actionName($param1, $param2, $param3);
                        break;
                    default:
                        break;
                }
                
                /* if parameters(lang) section is emty, then setting default(tm) lang */
                /*if (!empty($parameters))
                    $result = $controllerObject->$actionName($parameters);
                else
                    $result = $controllerObject->$actionName();
                 * 
                 */
                if ($result != null) {
                    break;
                }
            }
        }
    }

}

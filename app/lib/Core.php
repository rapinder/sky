<?php

/*
* App core class
* Creates url and loads core controller
* URL Format /controller/method/params
*/

class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        // Look in controllers for first value
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            // If exists, set as controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 Index
            unset($url[0]);
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if (isset($url[1])) {
            // Check if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array if params

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

        // TODO Pleaes make sure not to load other controller. sky/post/1 will load pages
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            // Remove ending slash
            $url = rtrim($_GET['url'], '/');
            // Sanitize url
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
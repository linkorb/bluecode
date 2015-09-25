<?php

namespace BlueCode\Model;

use BlueCode\Model\Route;

class Project
{
    private $code;
    
    public function getCode()
    {
        return $this->code;
    }
    
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    
    private $routes = array();
    
    public function getRoutes()
    {
        return $this->routes;
    }
    
    public function addRoute(Route $route)
    {
        $this->routes[$route->getName()] = $route;
        return $this;
    }
    
    public function getRoute($name)
    {
        return $this->routes[$name];
    }
    
}

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
    
    private $summary;
    
    public function getSummary()
    {
        return $this->summary;
    }
    
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }
    
    private $concepts = array();
    
    public function getConcept($name)
    {
        return $this->concepts[$name];
    }
    
    public function getConcepts()
    {
        return $this->concepts;
    }
    
    public function addConcept($concept)
    {
        $this->concepts[$concept->getName()] = $concept;
        return $this;
    }
    
    private $tables = array();
    
    public function getTables()
    {
        return $this->tables;
    }
    
    public function addTable(Table $table)
    {
        $this->tables[$table->getName()] = $table;
        return $this;
    }
    
    public function getTable($name)
    {
        return $this->tables[$name];
    }
    
    
}

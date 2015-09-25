<?php

namespace BlueCode\Model;

class Route
{
    private $path;
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function setPath($path)
    {
        $this->path = $path;
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
    
    
}

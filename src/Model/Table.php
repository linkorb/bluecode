<?php

namespace BlueCode\Model;

class Table
{
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
    
    private $columns = array();
    
    public function addColumn(Column $column)
    {
        $this->columns[$column->getName()] = $column;
    }
    
    public function getColumns()
    {
        return $this->columns;
    }
}

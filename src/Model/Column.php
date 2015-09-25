<?php

namespace BlueCode\Model;

class Column
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
    
    private $type;
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
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
    
    private $comment;
    
    public function getComment()
    {
        return $this->comment;
    }
    
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
    
    private $length;
    
    public function getLength()
    {
        return $this->length;
    }
    
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }
    
    private $autoIncrement;
    
    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }
    
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;
        return $this;
    }
    
    private $unsigned;
    
    public function getUnsigned()
    {
        return $this->unsigned;
    }
    
    public function setUnsigned($unsigned)
    {
        $this->unsigned = $unsigned;
        return $this;
    }
    
    
    
}

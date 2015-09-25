<?php

namespace BlueCode\Repository;

use BlueCode\Model\Project;
use BlueCode\Model\Route;
use BlueCode\Model\Concept;
use BlueCode\Model\Table;
use BlueCode\Model\Column;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use RuntimeException;

use PDO;

class FileProjectRepository
{
    private $projects;

    public function __construct($projects)
    {
        $this->projects = $projects;
    }

    public function getByCode($code)
    {
        $project = new Project();
        $project->setCode($code);
        $this->loadProject($project);
        return $project;
    }

    public function getAll()
    {
        $res = array();
        /*
        foreach(glob($this->path . '/*') as $file)
        {
            if (file_exists($file . '/bluecode.yml')) {
                $project = new Project();
                $project->setCode(basename($file));
                $this->loadProject($project);
                $res[]= $project;
            }
        }
        */

        foreach($this->projects as $code=>$path) {
            if (file_exists($path . '/bluecode.yml')) {
                $project = new Project();
                $project->setCode($code);
                $this->loadProject($project);
                $res[]= $project;
            }
        }
        return $res;
    }
    
    private function loadProject(Project $project)
    {
        $path = $this->projects[$project->getCode()];
        $filename = $path . '/bluecode.yml';
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $parser = new YamlParser();
        $config = $parser->parse(file_get_contents($filename));
        $project->setName($config['name']);
        $project->setDescription($config['description']);
        $project->setSummary($config['summary']);
        foreach ($config['concepts'] as $name => $description) {
            $c = new Concept();
            $c->setName($name);
            $c->setDescription($description);
            $project->addConcept($c);
        }

        $locator = new FileLocator(array($path));
        $loader = new YamlFileLoader($locator);
        $routes = $loader->load('routes.yml');
        
        foreach ($routes as $name => $route) {
            $r = new Route();
            $r->setName($name);
            $r->setPath($route->getPath());
            $defaults = $route->getDefaults();
            $r->setController($defaults['_controller']);
            $doc = $route->getOptions()['bluecode'];
            if (isset($doc['summary'])) {
                $r->setSummary($doc['summary']);
            }
            if (isset($doc['description'])) {
                $r->setDescription($doc['description']);
            }
            $project->addRoute($r);
        }
        
        
        $xml = simplexml_load_file($path .'/schema.xml');
        foreach ($xml as $tableNode) {
            //print_r($tableNode);
            $table = new Table();
            $table->setName((string)$tableNode['name']);
            $table->setDescription((string)$tableNode['description']);
            foreach ($tableNode as $columnNode) {
                $column = new Column();
                $column->setName((string)$columnNode['name']);
                $column->setType((string)$columnNode['type']);
                $column->setLength((string)$columnNode['length']);
                $column->setComment((string)$columnNode['comment']);
                $column->setAutoIncrement((string)$columnNode['autoincrement']);
                $column->setUnsigned((string)$columnNode['unsigned']);
                $table->addColumn($column);
            }
            $project->addTable($table);
        }
        //exit();
        //print_r($routes); exit();
        
    }
}

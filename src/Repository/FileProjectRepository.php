<?php

namespace BlueCode\Repository;

use BlueCode\Model\Project;
use BlueCode\Model\Route;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

use PDO;

class FileProjectRepository
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
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
        foreach(glob($this->path . '/*') as $file)
        {
            //echo "filename: `$file` : filetype: " . filetype($file) . "<br />";
            if (file_exists($file . '/bluecode.yml')) {
                $project = new Project();
                $project->setCode(basename($file));
                $this->loadProject($project);
                $res[]= $project;
            }
        }
        return $res;
    }
    
    private function loadProject(Project $project)
    {
        $path = $this->path . '/' . $project->getCode();
        $filename = $path . '/bluecode.yml';
        $parser = new YamlParser();
        $config = $parser->parse(file_get_contents($filename));
        $project->setName($config['name']);
        $project->setDescription($config['description']);

        $locator = new FileLocator(array($path));
        $loader = new YamlFileLoader($locator);
        $routes = $loader->load('routes.yml');
        
        foreach ($routes as $name => $route) {
            $r = new Route();
            $r->setName($name);
            $r->setPath($route->getPath());
            $doc = $route->getOptions()['bluecode'];
            if (isset($doc['summary'])) {
                $r->setSummary($doc['summary']);
            }
            if (isset($doc['description'])) {
                $r->setDescription($doc['description']);
            }
            $project->addRoute($r);
        }
        //print_r($routes); exit();
        
    }
}

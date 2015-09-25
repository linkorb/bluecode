<?php

namespace BlueCode\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BlueCode\Model\Thing;

class DocController
{
    public function frontpageAction(Application $app, Request $request)
    {
        return new Response($app['twig']->render(
            'frontpage.html.twig'
        ));
    }

    public function indexAction(Application $app, Request $request)
    {
        $repo = $app->getProjectRepository();
        $projects = $repo->getAll();

        $data = array();
        $data['projects'] = $projects;
        return new Response($app['twig']->render(
            'projects/index.html.twig',
            $data
        ));
    }

    public function viewAction(Application $app, Request $request, $projectCode)
    {
        $repo = $app->getProjectRepository();
        $project = $repo->getByCode($projectCode);

        $data = array();
        $data['project'] = $project;
        return new Response($app['twig']->render(
            'projects/view.html.twig',
            $data
        ));
    }

    public function routeViewAction(Application $app, Request $request, $projectCode, $routeName)
    {
        $repo = $app->getProjectRepository();
        $project = $repo->getByCode($projectCode);
        $route = $project->getRoute($routeName);

        $data = array();
        $data['project'] = $project;
        $data['route'] = $route;
        
        return new Response($app['twig']->render(
            'projects/route.html.twig',
            $data
        ));
    }

}

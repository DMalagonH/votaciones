<?php

namespace AT\votacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VotacionBundle:Default:index.html.twig', array('name' => $name));
    }
}

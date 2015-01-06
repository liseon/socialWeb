<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/tt3", name="testpage")
     *
     */
    public function indexAction($name)
    {
        return $this->render('AcmeMainBundle:Default:index.html.twig', array('name' => $name));
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/test/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // is it an Ajax request?
        $isAjax = $request->isXmlHttpRequest();

        // what's the preferred language of the user?
        $language = $request->getPreferredLanguage(array('en', 'ru'));

        // get the value of a $_GET parameter
        $pageName = $request->query->get('page');

        // get the value of a $_POST parameter
        $pageName = $request->request->get('page');

        $session = $request->getSession();

        // store an attribute for reuse during a later user request
        $session->set('foo', 'bar');

        // get the value of a session attribute
        $foo = $session->get('foo');

        // use a default value if the attribute doesn't exist
        $foo = $session->get('foo', 'default_value');

        //return (new Response("Hello"))->headers->setCookie();
        //return $this->render('default/index.html.twig');
    }

    /**
     * @Route(
     *     "/test/hello/{name}.{_format}",
     *     defaults={"_format"="html", "name"=""},
     *     requirements = { "_format" = "html|xml|json" },
     *     name="hello"
     * )
     * @param string $name
     * @return Response A Response instance
     */
    public function helloAction($name, $_format)
    {
        return $this->render("default/hello.{$_format}.twig", array(
            'name' => $name
        ));
    }
}

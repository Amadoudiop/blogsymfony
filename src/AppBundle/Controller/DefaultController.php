<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("homepage")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage_cool")
     * @Method("GET")
     */

    public function indexAction(Request $request)
    {
//        dump('test');die;
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findAll();
        $slides = $em->getRepository('AppBundle:Slide')->findAll();

        return $this->render(
            'default/index.html.twig',
            [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                'articles' => $articles,
                'slides' => $slides,

            ]);
    }

}
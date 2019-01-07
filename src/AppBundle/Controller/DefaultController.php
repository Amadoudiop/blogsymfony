<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage_cool")
     * @Method("GET")
     */

    public function indexAction(Request $request)
    {
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

    /**
     * @Route("/AccessDeniedAction", name="AccessDeniedAction")
     * @Method("GET")
     */
    public function AccessDeniedAction()
    {
        return $this->render('403.html.twig');
    }

}
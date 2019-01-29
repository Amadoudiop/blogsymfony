<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage_cool")
     * @Method("GET")
     */
    public function indexAction(request $request)
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
     * Lists all article entities valid.
     *
     * @Route("listArticleHome", name="list_article_home", options={"expose"=true})
     * @Method("GET")
     */
    public function listArticleAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $q = $qb
            ->select('e')
            ->from('AppBundle:element','e')
            ->leftJoin('AppBundle:article','a','WITH','e.article = a.id')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('a.enabled',1),
                    $qb->expr()->lt('e.dateCreate', ':dateCreate')
                )
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('e.dateCreate', 'DESC')
            ->setMaxResults(20)
            ->getQuery();
        $elements = $q->getResult();
        $data = "";
        if (!empty($elements)) {
            foreach ($elements as $element) {
                $email = $element->getArticle();
                $email = $email->getUser();
                $email = $email->getEmail();
                $data .= $this->render('article\article.html.twig', array(
                    'element' => $element,
                    'email' => $email,
                ))->getContent();
            }
        }else{
            $data="end";
        }
        return new JsonResponse($data);
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
<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\FileHandler;
use AppBundle\Service\SendMail;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

//use DateTime;

/**
 * Article controller.
 */
class ValidatorController extends Controller
{
    /**
     * show template validation.
     *
     * @Route("validation", name="validation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('validation\index.html.twig');
    }

    /**
     * Lists all article and user entities not valide.
     *
     * @Route("validation/moderate", name="moderate", options={"expose"=true})
     * @Method("GET")
     */
    public function moderateAction()
    {
        return $this->render('validation\index.html.twig');
    }


    /**
     * Lists all article entities not valide.
     *
     * @Route("ArticleNotValide", name="ArticleNotValideIndex", options={"expose"=true})
     * @Method("GET")
     */
    public function articleNotValideAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $q = $qb->select('a')
            ->from('AppBundle:article', 'a')
            ->where('a.enabled = 0')
            ->andWhere(
                $qb->expr()->lt('a.dateCreate', ':dateCreate')
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('a.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $users = $q->getResult();
        $data = "";
        if ($users) {
            foreach ($users as $table) {
                $data .= $this->render('article\article.html.twig', array(
                    'table' => $table,
                ));
            }
        }
        return new JsonResponse($data);
    }

    /**
     * Lists all article entities valide.
     *
     * @Route("ArticleValide", name="ArticleValideIndex", options={"expose"=true})
     * @Method("GET")
     */
    public function articleValideAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $q = $qb->select('a')
            ->from('AppBundle:article', 'a')
            ->where('a.enabled = 1')
            ->andWhere(
                $qb->expr()->lt('a.dateCreate', ':dateCreate')
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('a.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $users = $q->getResult();
        $data = "";
        if ($users) {
            foreach ($users as $table) {
                $data .= $this->render('article\article.html.twig', array(
                    'table' => $table,
                ));
            }
        }
        return new JsonResponse($data);
    }


    /**
     * Lists all user entities not valide.
     *
     * @Route("UserNotValide", name="UserNotValideIndex", options={"expose"=true})
     * @Method("GET")
     */
    public function userNotValideAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $q = $qb->select('u')
            ->from('AppBundle:user', 'u')
            ->where('u.validation = 0')
            ->andWhere('u.enabled = 1')
            ->andWhere(
                $qb->expr()->lt('u.dateCreate', ':dateCreate')
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('u.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $users = $q->getResult();

        $data = "";
        if ($users) {
            foreach ($users as $table) {
                $data .= $this->render('article\user.html.twig', array(
                    'table' => $table,
                ));
            }
        }
        return new JsonResponse($data);
    }

    /**
     * Lists all user entities valide.
     *
     * @Route("UserValide", name="UserValideIndex", options={"expose"=true})
     * @Method({"GET" ,"POST"})
     */

    public function userValideAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $q = $qb->select('u')
            ->from('AppBundle:user', 'u')
            ->where('u.validation = 1')
            ->andWhere('u.enabled = 1')
            ->andWhere(
                $qb->expr()->lt('u.dateCreate', ':dateCreate')
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('u.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $users = $q->getResult();

        $data = "";
        if ($users) {
            foreach ($users as $table) {
                $data .= $this->render('article\user.html.twig', array(
                    'table' => $table,
                ));
            }
        }
        return new JsonResponse($data);
    }

    /**
     * Lists all user entities admin.
     *
     * @Route("userAdmin", name="userAdminIndex")
     * @Method("GET")
     */
    public function userAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findByRole("ROLE_ADMIN");
        $objectCollection = new ArrayCollection();

        foreach ($users as $user) {
            $objectCollection->add($user);
        }
        $iterator = $objectCollection->getIterator();
        $iterator->uasort(function ($a, $b) {

            return ($a->getDateCreate() < $b->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\validation.html.twig', array(
            'tables' => $iterator,
        ));
    }

    /**
     * accept one article
     *
     * @Route("/{id}/accept", name="article_accept", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function acceptAction(Article $article)
    {
        $article->setEnabled(1);
        $this->getDoctrine()->getManager()->flush();

        if ($article->getUser() != null) {
            $SendMail = $this->get(SendMail::class);
            $SendMail->SendMail('- MakeMeUp article refusé -',
                $article->getUser()->getEmail(),
                'ArticleAccept');
        }

        return new JsonResponse(1);
    }

    /**
     * denied one article
     *
     * @Route("/{id}/refuse", name="article_refuse", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function refuseAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        if ($article->getUser() != null) {
            $SendMail = $this->get(SendMail::class);
            $SendMail->SendMail('- MakeMeUp article refusé -',
                $article->getUser()->getEmail(),
                'ArticleRefus');
        }

        return new JsonResponse(1);
    }
}

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
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;

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
     * @Route("listAllNotValid", name="list_all_not_valid", options={"expose"=true})
     * @Method("GET")
     */
    public function moderateAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $q = $qb
            ->select('e')
            ->from('AppBundle:element','e')
            ->leftJoin('AppBundle:article','a','WITH','e.article = a.id')
            ->leftJoin('AppBundle:user','u','WITH','e.user = u.id')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('a.enabled',0),
                    $qb->expr()->lt('e.dateCreate', ':dateCreate')
                )
            )
           ->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq('u.enabled',1),
                    $qb->expr()->eq('u.validation',0),
                    $qb->expr()->lt('e.dateCreate', ':dateCreate')
                )
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('e.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $elements = $q->getResult();
        $data = "";

        if ($elements) {
            foreach ($elements as $element) {
                if(($element->getUser()) != null ) {
                    $data .= $this->render('article\user.html.twig', array(
                        'element' => $element,
                    ));
                }
                else if(($element->getArticle()) != null ){
                    $data .= $this->render('article\article.html.twig', array(
                        'element' => $element,
                    ));
                }
            }
        }
        return new JsonResponse($data);
    }


    /**
     * Lists all article entities not valid.
     *
     * @Route("listArticleNotValid", name="list_article_not_valid_index", options={"expose"=true})
     * @Method("GET")
     */
    public function listArticleNotValidAction(request $request)
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
                    $qb->expr()->eq('a.enabled',0),
                    $qb->expr()->lt('e.dateCreate', ':dateCreate')
                )
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('e.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $elements = $q->getResult();
        $data = "";
        if ($elements) {
            foreach ($elements as $element) {
                $data .= $this->render('article\article.html.twig', array(
                    'element' => $element,
                ));
            }
        }
        return new JsonResponse($data);
    }

    /**
     * Lists all article entities valid.
     *
     * @Route("listArticleValid", name="list_article_valid_index", options={"expose"=true})
     * @Method("GET")
     */
    public function listArticleValidAction(request $request)
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
            ->setMaxResults(10)
            ->getQuery();
        $elements = $q->getResult();
        $data = "";
        if ($elements) {
            foreach ($elements as $element) {
                $data .= $this->render('article\article.html.twig', array(
                    'element' => $element,
                ));
            }
        }
        return new JsonResponse($data);
    }


    /**
     * Lists all user entities not valide.
     *
     * @Route("listUserNotValid", name="list_user_not_valid_index", options={"expose"=true})
     * @Method("GET")
     */
    public function listUserNotValidAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $q = $qb
            ->select('e')
            ->from('AppBundle:element','e')
            ->leftJoin('AppBundle:user','u','WITH','e.user = u.id')
            ->Where(
                $qb->expr()->andX(
                    $qb->expr()->eq('u.enabled',1),
                    $qb->expr()->eq('u.validation',0),
                    $qb->expr()->lt('e.dateCreate', ':dateCreate')
                )
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('e.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $elements = $q->getResult();
        $data = "";

        if ($elements) {
            foreach ($elements as $element) {
                $data .= $this->render('article\user.html.twig', array(
                    'element' => $element,
                ));
            }
        }

        return new JsonResponse($data);
    }

    /**
     * Lists all user entities valide.
     *
     * @Route("UserValid", name="list_user_valid_index", options={"expose"=true})
     * @Method({"GET" })
     * @Todo faire en sorte que l'on arrive à récupérer l'ID 1 de la table user sans erreurs.
     */
    public function listUserValidAction(request $request)
    {
        $lastElementDate = $request->request->get("lastElementDate");
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $q = $qb
            ->select('e')
            ->from('AppBundle:element','e')
            ->leftJoin('AppBundle:user','u','WITH','e.user = u.id')
            ->Where(
                $qb->expr()->andX(
                    $qb->expr()->neq('u.id',1),
                    $qb->expr()->eq('u.enabled',1),
                    $qb->expr()->eq('u.validation',1),
                    $qb->expr()->lt('e.dateCreate', ':dateCreate')
                )
            )
            ->setParameter('dateCreate', $lastElementDate)
            ->orderBy('e.dateCreate', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        $elements = $q->getResult();
        $data = "";
        if (!empty($elements)) {
            foreach ($elements as $element) {
                if(($element->getUser()) != null ) {
                    $data .= $this->render('article\user.html.twig', array(
                        'element' => $element,
                    ));
                }
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

            return ($a->getElement()->getDateCreate() < $b->getElement()->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\validation.html.twig', array(
            'tables' => $iterator,
        ));
    }


}

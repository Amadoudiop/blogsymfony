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
class ArticleController extends Controller
{
    /**
     * Lists all article entities not valide.
     *
     * @Route("ArticleNotValide", name="ArticleNotValideIndex")
     * @Method("GET")
     */

    public function articleNotValideAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findByEnabled(0);
        $table = array_merge($articles);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
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
     * Lists all article entities valide.
     *
     * @Route("ArticleValide", name="ArticleValideIndex")
     * @Method("GET")
     */
    public function articleValideAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findByEnabled(1);
        $table = array_merge($articles);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
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
     * Lists all article create by one user.
     *
     * @Route("ArticleUser", name="ArticleUser")
     * @Method("GET")
     */
    public function articleUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findByUser($this->getUser()->getId());
        $table = array_merge($articles);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
        }
        $iterator = $objectCollection->getIterator();
        $iterator->uasort(function ($a, $b) {

            return ($a->getDateCreate() < $b->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\articlePersoUser.html.twig', array(
            'tables' => $iterator,
        ));
    }

    /**
     * Lists all user entities not valide.
     *
     * @Route("UserNotValide", name="UserNotValideIndex")
     * @Method("GET")
     */
    public function userNotValideAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findBy(array('validation' => 0,
            'enabled' => 1));
        $table = array_merge($users);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
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
     * Lists all article and user entities not valide.
     *
     * @Route("validation", name="validation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findByEnabled(0);
        $users = $em->getRepository('AppBundle:User')->findBy(array('validation' => 0,
            'enabled' => 1));
        $table = array_merge($users, $articles);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
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
     * @Route("/{id}/accept", name="article_accept")
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

        return $this->redirectToRoute('validation_index');
    }

    /**
     * denied one article
     *
     * @Route("/{id}/refuse", name="article_refuse")
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

        return $this->redirectToRoute('validation_index');
    }

    /**
     * Creates a new article entity.
     *
     * @Route("article/new", name="article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('AppBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $article->getPicture();
            $fileHandler = $this->get(FileHandler::class);
            $fileName = $fileHandler->upload($file, $this->getParameter('upload_directory'));
            if (!$fileName) {
                $this->addFlash(
                    'danger',
                    'la photo est trop grosse taille max 3MB'
                );
            } else {
                $article->setPicture($fileName["name"]);
                $article->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->addFlash(
                    'success',
                    'votre article à été envoyer à la modération'
                );

                return $this->redirectToRoute('article_show', array('id' => $article->getId()));
            }
        }

        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("article/{id}", name="article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("article/{id}/edit", name="article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
        }

        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     * @Route("article/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('validation_index');
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

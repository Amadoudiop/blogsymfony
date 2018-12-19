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
//use DateTime;

/**
 * Article controller.
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities not valide.
     *
     * @Route("ArticleNotValide", name="ArticleNotValide_index")
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
        $iterator->uasort(function ($a, $b){

            return ($a->getDateCreate() < $b->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\index.html.twig', array(
            'tables' => $iterator,
        ));
    }

    /**
     * Lists all article entities valide.
     *
     * @Route("ArticleValide", name="ArticleValide_index")
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
        $iterator->uasort(function ($a, $b){

            return ($a->getDateCreate() < $b->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\index.html.twig', array(
            'tables' => $iterator,
        ));
    }

    /**
     * Lists all user entities not valide.
     *
     * @Route("UserNotValide", name="UserNotValide_index")
     * @Method("GET")
     */
    public function userNotValideAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findByEnabled(0);
        $table = array_merge($users);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
        }
        $iterator = $objectCollection->getIterator();
        $iterator->uasort(function ($a, $b){

            return ($a->getDateCreate() < $b->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\index.html.twig', array(
            'tables' => $iterator,
        ));
    }

    /**
     * Lists all user entities valide.
     *
     * @Route("UserValide", name="UserValide_index")
     * @Method("GET")
     */
    public function userValideAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findByEnabled(1);
        $table = array_merge($users);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
        }
        $iterator = $objectCollection->getIterator();
        $iterator->uasort(function ($a, $b){

            return ($a->getDateCreate() < $b->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\index.html.twig', array(
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
        $users = $em->getRepository('AppBundle:User')->findByEnabled(0);
        $table = array_merge($users,$articles);
        $objectCollection = new ArrayCollection();

        foreach ($table as $object) {
            $objectCollection->add($object);
        }
        $iterator = $objectCollection->getIterator();
        $iterator->uasort(function ($a, $b){

            return ($a->getDateCreate() < $b->getDateCreate()) ? -1 : 1;
        });

        return $this->render('article\index.html.twig', array(
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

        if ($article->getUser() != null ) {
            $SendMail = $this->get(SendMail::class);
            $SendMail->SendMail('- MakeMeUp article refusé -',
                                $article->getUser()->getEmail(),
                                'ArticleAccept' );
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

        if ($article->getUser() != null ) {
            $SendMail = $this->get(SendMail::class);
            $SendMail->SendMail('- MakeMeUp article refusé -',
                                $article->getUser()->getEmail(),
                                'ArticleRefus' );
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
            $article->setPicture($fileName["name"]);
            $id_user = $this->getUser()->getId();
            $article->setIdUser($id_user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_show', array('id' => $article->getId()));
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
            ->getForm()
        ;
    }
}

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
use Twig\Token;

//use DateTime;

/**
 * Article controller.
 */
class ArticleController extends Controller
{
    /**
     * accept one article
     *
     * @Route("/{id}/accept", name="article_accept", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function articleAcceptAction(Article $article, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(0);
        }
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
    public function articleRefuseAction(Article $article, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(0);
        }
        $em = $this->getDoctrine()->getManager();
        $element = $article->getElement();
        $em->remove($element);
        $em->flush();

        if ($article->getUser() != null) {
            $SendMail = $this->get(SendMail::class);
            $SendMail->SendMail('- MakeMeUp article refusé -',
                $article->getUser()->getEmail(),
                'ArticleRefus');
        }

        return new JsonResponse(1);
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

            return ( $a->getElement()->getDateCreate() < $b->getElement()->getDateCreate() ) ? -1 : 1;
        });

        return $this->render('article\articlePersoUser.html.twig', array(
            'elements' => $iterator,
        ));
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
            $file = $article->getPictureUpload();
            $fileHandler = $this->get(FileHandler::class);
            $fileName = $fileHandler->upload($file, $this->getParameter('upload_directory'));
            if (!$fileName) {
                $this->addFlash(
                    'danger',
                    "'la photo n'est pas au bon format"
                );
            } else {
                $article->setPicture($fileName["name"]);
                $article->setPictureUpload(null);
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
        $element = $article->getElement();
        $article = $element->getArticle();
        $email = $article->getUser();
        $id = $email->getId();
        $email = $email->getEmail();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId= $user ->getId();
        $admin = 0;
        if(in_array("ROLE_ADMIN", $user->getRoles())){
            $admin = 1;
        }

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'element' => $element,
            'email' => $email,
            'id_user_article' => $id,
            'id_user' => $userId,
            'admin' => $admin,
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
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if($article->getEnabled() != 1){
            if( (( $article->getUser()->getId() != $user->getId() )
                && (!in_array("ROLE_ADMIN", $user->getRoles())))){
                return $this->render('404.html.twig');
            }
        }else{
            if(!in_array("ROLE_ADMIN", $user->getRoles())){
                return $this->render('404.html.twig');;
            }
        }

        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $article->getPictureUpload();
            if(!is_null($file)){
                $fileHandler = $this->get(FileHandler::class);
                $fileName = $fileHandler->upload($file, $this->getParameter('upload_directory'));
                if (!$fileName) {
                    $this->addFlash(
                        'danger',
                        'la photo est trop grosse taille max 3MB'
                    );
                } else {
                    $article->setPicture($fileName["name"]);
                    $article->setPictureUpload(null);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    $this->addFlash(
                        'success',
                        'votre article à été modifié'
                    );
                    $this->getDoctrine()->getManager()->flush();
                }
            }

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
     * @Route("article/{id}/delete", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        dump($request->headers);
        die;
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

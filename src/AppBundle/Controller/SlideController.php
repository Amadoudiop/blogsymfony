<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Slide;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\FileHandler;

/**
 * Slide controller.
 *
 * @Route("slide")
 */
class SlideController extends Controller
{
    /**
     * Lists all slide entities.
     *
     * @Route("/", name="slide_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $slides = $em->getRepository('AppBundle:Slide')->findAll();

        return $this->render('slide/index.html.twig', array(
            'slides' => $slides,
        ));
    }

    /**
     * Creates a new slide entity.
     *
     * @Route("/new", name="slide_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $slide = new Slide();
        $form = $this->createForm('AppBundle\Form\SlideType', $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $slide->getPicture();
            $fileHandler = $this->get(FileHandler::class);
            $fileName = $fileHandler->upload($file, $this->getParameter('upload_directory_slide'));
            $slide->setPicture($fileName["name"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($slide);
            $em->flush();

            return $this->redirectToRoute('slide_show', array('id' => $slide->getId()));
        }

        return $this->render('slide/new.html.twig', array(
            'slide' => $slide,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a slide entity.
     *
     * @Route("/{id}", name="slide_show")
     * @Method("GET")
     */
    public function showAction(Slide $slide)
    {
        $deleteForm = $this->createDeleteForm($slide);

        return $this->render('slide/show.html.twig', array(
            'slide' => $slide,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing slide entity.
     *
     * @Route("/{id}/edit", name="slide_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Slide $slide)
    {
        $deleteForm = $this->createDeleteForm($slide);
        $editForm = $this->createForm('AppBundle\Form\SlideType', $slide);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('slide_edit', array('id' => $slide->getId()));
        }

        return $this->render('slide/edit.html.twig', array(
            'slide' => $slide,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a slide entity.
     *
     * @Route("/{id}", name="slide_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Slide $slide)
    {
        $form = $this->createDeleteForm($slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slide);
            $em->flush();
        }

        return $this->redirectToRoute('slide_index');
    }

    /**
     * Creates a form to delete a slide entity.
     *
     * @param Slide $slide The slide entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Slide $slide)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('slide_delete', array('id' => $slide->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StatusModerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Statusmoderator controller.
 *
 * @Route("statusmoderator")
 */
class StatusModeratorController extends Controller
{
    /**
     * Lists all statusModerator entities.
     *
     * @Route("/", name="statusmoderator_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $statusModerators = $em->getRepository('AppBundle:StatusModerator')->findAll();

        return $this->render('statusmoderator/index.html.twig', array(
            'statusModerators' => $statusModerators,
        ));
    }

    /**
     * Creates a new statusModerator entity.
     *
     * @Route("/new", name="statusmoderator_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $statusModerator = new Statusmoderator();
        $form = $this->createForm('AppBundle\Form\StatusModeratorType', $statusModerator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($statusModerator);
            $em->flush();

            return $this->redirectToRoute('statusmoderator_show', array('id' => $statusModerator->getId()));
        }

        return $this->render('statusmoderator/new.html.twig', array(
            'statusModerator' => $statusModerator,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a statusModerator entity.
     *
     * @Route("/{id}", name="statusmoderator_show")
     * @Method("GET")
     */
    public function showAction(StatusModerator $statusModerator)
    {
        $deleteForm = $this->createDeleteForm($statusModerator);

        return $this->render('statusmoderator/show.html.twig', array(
            'statusModerator' => $statusModerator,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing statusModerator entity.
     *
     * @Route("/{id}/edit", name="statusmoderator_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, StatusModerator $statusModerator)
    {
        $deleteForm = $this->createDeleteForm($statusModerator);
        $editForm = $this->createForm('AppBundle\Form\StatusModeratorType', $statusModerator);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('statusmoderator_edit', array('id' => $statusModerator->getId()));
        }

        return $this->render('statusmoderator/edit.html.twig', array(
            'statusModerator' => $statusModerator,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a statusModerator entity.
     *
     * @Route("/{id}", name="statusmoderator_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, StatusModerator $statusModerator)
    {
        $form = $this->createDeleteForm($statusModerator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($statusModerator);
            $em->flush();
        }

        return $this->redirectToRoute('statusmoderator_index');
    }

    /**
     * Creates a form to delete a statusModerator entity.
     *
     * @param StatusModerator $statusModerator The statusModerator entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(StatusModerator $statusModerator)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('statusmoderator_delete', array('id' => $statusModerator->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

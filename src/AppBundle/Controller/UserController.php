<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Element;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\SendMail;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Accept one user
     *
     * @Route("user/{id}/accept", name="user_accept", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function userAcceptAction(User $user)
    {
        $user->setValidation(1);
        $this->getDoctrine()->getManager()->flush();

        $SendMail = $this->get(SendMail::class);
        $SendMail->SendMail('- MakeMeUp Contact compte accepte -',
            $user->getEmail(),
            'CompteAccepte' );

        return new JsonResponse(1);
    }
    /**
     * refuse one user
     *
     * @Route("user/{id}/refuse", name="user_refuse", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function userRefuseAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $element = $user->getElement();
        $em->remove($element);
        $em->flush();

        $SendMail = $this->get(SendMail::class);
        $SendMail->SendMail('- MakeMeUp Contact compte refusé -',
            $user->getEmail(),
            'CompteRefuse' );

        return new JsonResponse(1);
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

            return ( $a->getElement()->getDateCreate() < $b->getElement()->getDateCreate() ) ? -1 : 1;
        });

        return $this->render('article\validation.html.twig', array(
            'tables' => $iterator,
        ));
    }

    /**
     * make one user admin
     *
     * @Route("user/{id}/setAdmin", name="set_admin", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function userSetAdminAction(User $user)
    {
        $user->addRole("ROLE_ADMIN");
        $this->getDoctrine()->getManager()->flush();

        $SendMail = $this->get(SendMail::class);
        $SendMail->SendMail('- MakeMeUp Contact compte accepte -',
            $user->getEmail(),
            'CompteAccepte' );

        return new JsonResponse(1);
    }

    /**
     * unset one user admin
     *
     * @Route("user/{id}/unsetAdmin", name="unset_admin", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function userUnsetAdminAction(User $user)
    {
        $user->removeRole("ROLE_ADMIN");
        $this->getDoctrine()->getManager()->flush();

        $SendMail = $this->get(SendMail::class);
        $SendMail->SendMail('- MakeMeUp Contact compte accepte -',
            $user->getEmail(),
            'CompteAccepte' );

        return new JsonResponse(1);
    }
    
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Lists all user entities.
     *
     * @Route("/login-register", name="user_login_register")
     * @Method("GET")
     */
    public function loginRegisterAction()
    {
        return $this->render('login_and_registration_page.html.twig');
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $element = new Element();
            $element->setUser($user);
            $em->persist($element);
            $em->flush();


            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $element = $user->getElement();

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'element' => $element,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("contact")
 */


class ContactController extends Controller
{
    /**
     * @Route("/", name="contact_cool")
     * @Method("GET")
     */

    public function indexAction(Request $request)
    {;

        $form = $this->createFormBuilder()
           ->add('NomPrenom', TextType::class, array('label' => false, 'attr' => array('placeholder' => 'Nom / Prènom', 'autocomplete'=>"off")))
           ->add('Email', EmailType::class, array('label' => false, 'attr' => array('placeholder' => 'Email')))
           ->add('Objet', TextType::class, array('label' => false, 'attr' => array('placeholder' => 'Objet', 'autocomplete'=>"off")))
           ->add('Message', TextareaType::class, array('label' => false, 'attr' => array('placeholder' => 'Message')))
           ->getForm();

    $form->handleRequest($request);
    $tplContact = 'contact/contact.html.twig';

        if ($form->isSubmitted() && $form->isValid()) {

            $data=$form->getData();

            $messagemail = $this->renderView('contact/mailDeContact.html.twig',
                array(  'nomPrenom' =>  $data['NomPrenom'],
                        'message' => $data['Message']
                ));

            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('MakeMeUp Contact -'.($form['Objet']->getData()).'-')
                ->setFrom($data['Email'])
                ->setTo('rdroro683@gmail.com')
                ->setBody($messagemail);
            $test = $this->get('mailer')->send($message);

            if ($test == 1) {
                $this->addFlash("success", "Message envoyé !");
                return $this->render($tplContact, array(
                    'envoie' => 1,
                ));
            }
            if ($test == 0) {
                $this->addFlash("danger", "Erreure lors de l'envoie le message ne c'est pas envoyer !");
                return $this->render($tplContact, array(
                    'envoie' => 2,
                ));
            }
        }

        return $this->render($tplContact, array(
            'form' => $form->createView(),
            'envoie' => 0,

        ));
    }

}

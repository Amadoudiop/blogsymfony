<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('date_create', DateType::class)
//            ->add('date_update', DateType::class)
            ->add('event', RadioType::class,array(
                'label'    => 'event',
                'required' => false
            ))
            ->add('date_event', DateType::class, ['data' =>  new \DateTime('now')])
            ->add('title', TextType::class)
            ->add('catch_sentence', TextType::class)
            //->add('picture', FileType::class, array('label' => 'picture (PDF file)'))
            ->add('content', TextareaType::class, array('empty_data' => '',
                                                                    'required' => false,))
            ->add('category', EntityType::class,
                [
                    'class' => 'AppBundle:Category',
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_article';
    }


}

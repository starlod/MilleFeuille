<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('type')
            // ->add('status')
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            // ->add('content', CKEditorType::class, array(
            //     'config' => array(
            //         'uiColor' => '#ffffff',
            //         //...
            //     ),
            // ))
            // ->add('metaTitle', TextType::class)
            // ->add('metaDescription', TextareaType::class)
            // ->add('publishedAt', DateTimeType::class, array(
            //     'date_widget' => 'single_text',
            //     'time_widget' => 'single_text',
            //     'format' => 'yyyy年MM月dd日',
            //     'empty_value' => new \DateTime(),
            // ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }
}

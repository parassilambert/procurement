<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ContactPersonType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
class ContactPersonType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('email', EmailType::class,array('label'=>false));
        $builder->add('firstName', TextType::class,array('label'=>false));
        $builder->add('lastName', TextType::class,array('label'=>false));
        $builder->add('address', TextareaType::class,array('label'=>false));
        $builder->add('phoneNumber', TextType::class,array('label'=>false));
     }
    
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EconomicUser'
        ));
    }
     public function getName()
    {
        return 'contact_person';
    }
}

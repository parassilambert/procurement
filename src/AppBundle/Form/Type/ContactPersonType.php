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

class ContactPersonType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('email', 'email',array('label'=>false));
        $builder->add('firstName', 'text',array('label'=>false));
        $builder->add('lastName', 'text',array('label'=>false));
        $builder->add('address', 'textarea',array('label'=>false));
        $builder->add('phoneNumber', 'text',array('label'=>false));
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

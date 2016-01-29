<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ChangePasswordType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('password', 'repeated', array('first_name'  => 'New_password','second_name' => 'Confirm_password','type'=> 'password','label'=>false));
     }
    
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EconomicUser'
        ));
    }
     public function getName()
    {
        return 'economic_user_password_change';
    }
}

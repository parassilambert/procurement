<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ContractingUserProfileType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractingUserProfileType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', 'text',array('label'=>false));
        $builder->add('lastname', 'text',array('label'=>false));
        $builder->add('email', 'email',array('label'=>false));
      
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContractingUser'
        ));
    }
    
    public function getName() {
         return 'contracting_user_profile';
    }
}

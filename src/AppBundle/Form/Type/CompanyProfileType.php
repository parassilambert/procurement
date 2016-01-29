<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of CompanyProfileType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyProfileType extends AbstractType{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('companyName', 'text',array('label'=>false));
        $builder->add('taxCountry', 'country',array('label'=>false));
        $builder->add('companyRegistrationNumber', 'text',array('label'=>false));
        $builder->add('taxId', 'text',array('label'=>false));
     }
    
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EconomicUser'
        ));
    }
     public function getName()
    {
        return 'economic_user_company_profile';
    }
}

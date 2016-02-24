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
use Symfony\Component\Form\Extension\Core\Type\CountryType;
class CompanyProfileType extends AbstractType{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('companyName', TextType::class,array('label'=>false));
        $builder->add('taxCountry', CountryType::class,array('label'=>false));
        $builder->add('companyRegistrationNumber', TextType::class,array('label'=>false));
        $builder->add('taxId', TextType::class,array('label'=>false));
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

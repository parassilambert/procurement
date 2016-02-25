<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ShortlistType
 *
 * @author lambert
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PreQualifiedType extends AbstractType{
      public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('companyName',EntityType::class,  array(
       'class' => 'AppBundle:EconomicUser',
       'query_builder' => function(\AppBundle\Repository\EconomicUserRepository $er) {
       return $er->createQueryBuilder('u')
                ->groupBy('u.id')
                ->orderBy('u.firstname', 'ASC');
         },
        'label' => false        
        )
       );
       $builder->add('Save', SubmitType::class);
    }
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PreQualified'
        ));
    }
    
    public function getName() {
         return 'prequalified_supplier';
    }

   
}

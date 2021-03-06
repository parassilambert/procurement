<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ContractEvaluationType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ContractEvaluationType extends AbstractType{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
       {
        $builder->add('creterion', TextType::class,array('label' => 'Creterion:','constraints' => array(new NotBlank())));
        $builder->add('weighting', IntegerType::class, array('label' => 'Weighting','constraints' => array(new NotBlank())));
       }
       
    public function configureOptions(OptionsResolver $resolver)
       {
         $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\ContractEvaluation',
             ));
       }
       
    public function getName() {
        return 'contract_evaluation';
    }

}

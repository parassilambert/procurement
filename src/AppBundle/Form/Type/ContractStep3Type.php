<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ContractStep3Type
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContractStep3Type extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('guaranteeCondition', TextareaType::class);
        $builder->add('financialCondition', TextareaType::class);
        $builder->add('legalCondition', TextareaType::class);
        $builder->add('hasOtherCondition', ChoiceType::class,array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $builder->add('otherCondition', TextareaType::class);
        $builder->add('eligibility', TextareaType::class);
        $builder->add('financialEvaluation', TextareaType::class);
        $builder->add('financialMinLevel', TextareaType::class);
        $builder->add('technicalEvaluation', TextareaType::class);
        $builder->add('technicalMinLevel', TextareaType::class);
        $builder->add('save', SubmitType::class, array('label' => 'Save'));
        $builder->add('nextStep',  SubmitType::class, array('label' => 'Next'));
        $builder->add('previousStep', SubmitType::class,array('validation_groups' => false,'label' => 'Previous')); 
     }
    
    public function configureOptions(OptionsResolver $resolver)
     {
           $resolver->setDefaults(array('validation_groups' => array('validationStep3')
        ));
     }
    
    public function getName() {
        return 'contract_entity_step3';
     }
}

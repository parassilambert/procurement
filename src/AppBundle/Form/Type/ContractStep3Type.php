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

class ContractStep3Type extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('guaranteeCondition', 'textarea');
        $builder->add('financialCondition', 'textarea');
        $builder->add('legalCondition', 'textarea');
        $builder->add('hasOtherCondition', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $builder->add('otherCondition', 'textarea');
        $builder->add('eligibility', 'textarea');
        $builder->add('financialEvaluation', 'textarea');
        $builder->add('financialMinLevel', 'textarea');
        $builder->add('technicalEvaluation', 'textarea');
        $builder->add('technicalMinLevel', 'textarea');
        $builder->add('save', 'submit', array('label' => 'Save'));
        $builder->add('nextStep','submit', array('label' => 'Next'));
        $builder->add('previousStep', 'submit',array('validation_groups' => false,'label' => 'Previous')); 
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

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ContractStep2Type
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractStep2Type extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('title', 'text');
        $builder->add('description','textarea');
        $builder->add('hasLotDivision', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No' => 'No'),
                       'expanded' => true));
        $builder->add('lot','integer');
        $builder->add('lotSubmissionType','choice',array('choices' => array(
                               'One lot only'   => 'One lot only',
                               'One or more lots' => 'One or more lots',
                               'All lots' => 'All lots'),
                               'expanded' => true));    
        $builder->add('hasVariants', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No' => 'No'),
                       'expanded' => true));
        $builder->add('scope', 'textarea');
        $builder->add('estimatedValueType', 'choice',array('choices' => array(
                       'Exact'   => 'Exact',
                       'Range' => 'Range'),
                       'expanded' => true));
        $builder->add('rangeStartValue', 'money');
        $builder->add('rangeEndValue', 'money');
        $builder->add('rangeCurrency', 'currency',array('placeholder' => 'Choose a currency')); 
        $builder->add('exactValue', 'money');
        $builder->add('exactCurrency', 'currency',array('placeholder' => 'Choose a currency'));
        $builder->add('contractDurationType', 'choice',array('choices' => array(
                       'Exact'   => 'Exact',
                       'Range' => 'Range'),
                       'expanded' => true));
        $builder->add('rangeStartDate','date');
        $builder->add('rangeEndDate', 'date');
        $builder->add('exactDurationType', 'choice',array('choices' => array(
                       'In months'   => 'In months',
                       'In days' => 'In days'),
                       'expanded' => true));
        $builder->add('exactDuration', 'integer');
        $builder->add('save', 'submit', array('label' => 'Save'));
        $builder->add('nextStep','submit', array('label' => 'Next'));
        $builder->add('previousStep', 'submit',array('validation_groups' => false,'label' => 'Previous'));
     }
        
    public function configureOptions(OptionsResolver $resolver)
    {
           $resolver->setDefaults(array('validation_groups' => array('validationStep2')
        ));
    }
    public function getName() {
        return 'contract_entity_step2';
    }
}

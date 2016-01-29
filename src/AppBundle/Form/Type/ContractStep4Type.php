<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ContractStep4Type
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\Type\ContractEvaluationType;

class ContractStep4Type extends AbstractType{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        
        $builder->add('awardCreteriaType', 'choice',array('choices' => array(
                       'The most economically advantegeous tender'   => 'The most economically advantegeous tender',
                       'Lowest price'    => 'Lowest price'),
                       'expanded' => true));
        $builder->add('contractEvaluation', 'collection', array('type' => new ContractEvaluationType(),'allow_add' => true,'by_reference' => false,'allow_delete' => true,'label'=>false));
        $builder->add('isPayableDocument', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $builder->add('price', 'money',array('required' => false));
        $builder->add('paymentCurrency', 'currency',array('required' => false));
        $builder->add('paymentTerms', 'textarea',array('required' => false));
        $builder->add('closingDate', 'datetime');
        $builder->add('openingDate', 'datetime');
        $builder->add('openingVenue', 'textarea');
        $builder->add('isPersonAuthorised', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $builder->add('authorisedPerson', 'textarea');
        $builder->add('save', 'submit', array('label' => 'Save'));
        $builder->add('previousStep', 'submit',array('validation_groups' => false,'label' => 'Previous'));
        $builder->add('nextStep','submit', array('label' => 'Next')); 
     }
    
    public function configureOptions(OptionsResolver $resolver)
     {
           $resolver->setDefaults(array('validation_groups' => array('validationStep4')
        ));
     }
    
    public function getName() {
        return 'contract_entity_step4';
     }
    
}

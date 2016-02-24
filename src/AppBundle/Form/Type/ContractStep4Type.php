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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ContractStep4Type extends AbstractType{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        
        $builder->add('awardCreteriaType', ChoiceType::class,array('choices' => array(
                       'The most economically advantegeous tender'   => 'The most economically advantegeous tender',
                       'Lowest price'    => 'Lowest price'),
                       'expanded' => true));
        $builder->add('contractEvaluation', CollectionType::class, array('type' => new ContractEvaluationType(),'allow_add' => true,'by_reference' => false,'allow_delete' => true,'label'=>false));
        $builder->add('isPayableDocument', ChoiceType::class,array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $builder->add('price', MoneyType::class,array('required' => false));
        $builder->add('paymentCurrency', CurrencyType::class,array('required' => false));
        $builder->add('paymentTerms', TextareaType::class,array('required' => false));
        $builder->add('closingDate', DateTimeType::class);
        $builder->add('openingDate', DateTimeType::class);
        $builder->add('openingVenue', TextareaType::class);
        $builder->add('isPersonAuthorised',  ChoiceType::class,array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $builder->add('authorisedPerson', TextareaType::class);
        $builder->add('save', SubmitType::class, array('label' => 'Save'));
        $builder->add('previousStep', SubmitType::class,array('validation_groups' => false,'label' => 'Previous'));
        $builder->add('nextStep',SubmitType::class, array('label' => 'Next')); 
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

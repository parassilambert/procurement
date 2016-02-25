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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContractStep2Type extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('title', TextType::class);
        $builder->add('description',TextareaType::class);
        $builder->add('hasLotDivision', ChoiceType::class,array('choices' => array(
                       'Yes'   => 'Yes',
                       'No' => 'No'),
                       'expanded' => true));
        $builder->add('lot',IntegerType::class);
        $builder->add('lotSubmissionType',ChoiceType::class,array('choices' => array(
                               'One lot only'   => 'One lot only',
                               'One or more lots' => 'One or more lots',
                               'All lots' => 'All lots'),
                               'expanded' => true));    
        $builder->add('hasVariants', ChoiceType::class,array('choices' => array(
                       'Yes'   => 'Yes',
                       'No' => 'No'),
                       'expanded' => true));
        $builder->add('scope', TextareaType::class);
        $builder->add('estimatedValueType', ChoiceType::class,array('choices' => array(
                       'Exact'   => 'Exact',
                       'Range' => 'Range'),
                       'expanded' => true));
        $builder->add('rangeStartValue', MoneyType::class);
        $builder->add('rangeEndValue', MoneyType::class);
        $builder->add('rangeCurrency', CurrencyType::class,array('placeholder' => 'Choose a currency')); 
        $builder->add('exactValue', MoneyType::class);
        $builder->add('exactCurrency', CurrencyType::class,array('placeholder' => 'Choose a currency'));
        $builder->add('contractDurationType', ChoiceType::class,array('choices' => array(
                       'Exact'   => 'Exact',
                       'Range' => 'Range'),
                       'expanded' => true));
        $builder->add('rangeStartDate',DateType::class);
        $builder->add('rangeEndDate', DateType::class);
        $builder->add('exactDurationType', ChoiceType::class,array('choices' => array(
                       'In months'   => 'In months',
                       'In days' => 'In days'),
                       'expanded' => true));
        $builder->add('exactDuration', IntegerType::class);
        $builder->add('save', SubmitType::class, array('label' => 'Save'));
        $builder->add('nextStep',SubmitType::class, array('label' => 'Next'));
        $builder->add('previousStep', SubmitType::class,array('validation_groups' => false,'label' => 'Previous'));
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

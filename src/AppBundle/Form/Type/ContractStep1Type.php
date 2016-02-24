<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of ContractStep1Type
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContractStep1Type extends AbstractType{
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('contractType', ChoiceType::class,array('choices' => array(
                       'Works'    => 'Works',
                       'Services' => 'Services',
                       'Supplies' => 'Supplies'),
                       'expanded' => true));
        $builder->add('procedureType', ChoiceType::class,array('choices' => array(
                       'Open'       => 'Open',
                       'Restricted' => 'Restricted'),
                       'expanded' => true));
        $builder->add('save', SubmitType::class, array('label' => 'Save'));
        $builder->add('nextStep',SubmitType::class, array('label' => 'Next'));
     }
        
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('validation_groups' => array('validationStep1')
        ));
    }
    public function getName() {
        return 'contract_entity_step1';
    }

}

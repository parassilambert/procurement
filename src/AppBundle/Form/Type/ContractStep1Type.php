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

class ContractStep1Type extends AbstractType{
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('contractType', 'choice',array('choices' => array(
                       'Works'    => 'Works',
                       'Services' => 'Services',
                       'Supplies' => 'Supplies'),
                       'expanded' => true));
        $builder->add('procedureType', 'choice',array('choices' => array(
                       'Open'       => 'Open',
                       'Restricted' => 'Restricted'),
                       'expanded' => true));
        $builder->add('save', 'submit', array('label' => 'Save'));
        $builder->add('nextStep','submit', array('label' => 'Next'));
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

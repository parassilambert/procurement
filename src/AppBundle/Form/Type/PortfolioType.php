<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of PortfolioType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PortfolioType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
       {
        
         $builder->add('title', 'text',array('label' => 'Title of document:','constraints' => array(new NotBlank())));
         $builder->add('file', 'file', array('label' => 'Document (PDF file):','constraints' => array(new NotBlank())));
       }
    public function configureOptions(OptionsResolver $resolver)
       {
         $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\Portfolio',
             ));
       } 
    
    public function getName() {
         return 'Portfolio_entity';
       }

}

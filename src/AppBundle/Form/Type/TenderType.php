<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TenderType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
       {
        
         $builder->add('title', TextType::class,array('label' => 'Title of document:','constraints' => array(new NotBlank())));
         $builder->add('file', FileType::class, array('label' => 'Document (PDF file):','constraints' => array(new NotBlank())));
       }
    public function configureOptions(OptionsResolver $resolver)
       {
         $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\Tender',
             ));
       } 
    
    public function getName() {
         return 'economic_user_tender';
       }

}

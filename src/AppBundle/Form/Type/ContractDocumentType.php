<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContractDocumentType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
       {
         $builder->add('title', 'text',array('label' => 'Title of document:','constraints' => array(new NotBlank())));
         $builder->add('file', 'file', array('label' => 'Document (PDF file):'));
       }
       
    public function configureOptions(OptionsResolver $resolver)
       {
         $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\ContractDocument',
             ));
       }
       
    public function getName() {
        return 'contract_document_entity';
    }

}

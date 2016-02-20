<?php
// src/AppBundle/Form/Type/ContractingUserRegistrationType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContractingUserRegistrationType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new ContractingUserType());      
        $builder->add('Register', 'submit');
    }
        
    public function getName() {
         return 'contracting_user_registration';
    }    
}

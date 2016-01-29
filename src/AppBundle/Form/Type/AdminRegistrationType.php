<?php
// src/AppBundle/Form/Type/AdminRegistrationType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminRegistrationType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new AdminUserType());      
        $builder->add('Register', 'submit');
    }
    
    
    public function getName() {
         return 'admin_registration';
    }    
}

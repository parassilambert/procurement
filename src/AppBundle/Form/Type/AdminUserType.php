<?php

// src/AppBundle/Form/Type/AdminUserType.php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType{
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', 'text');
        $builder->add('lastname', 'text');
        $builder->add('email', 'email');
        $builder->add('username', 'text');
        $builder->add('password', 'repeated', array('first_name'  => 'Create_password','second_name' => 'Confirm_password','type'=> 'password',));
    }
    
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AdminUser'
        ));
    }
    
    public function getName() {
         return 'admin_user';
    }

  
}

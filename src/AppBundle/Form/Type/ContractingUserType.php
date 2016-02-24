<?php

// src/AppBundle/Form/Type/ContractingUserType.php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ContractingUserType extends AbstractType{
    
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class);
        $builder->add('lastname', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('username', TextType::class);
        $builder->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,'first_options' => array('label' => 'Password'),'second_options' => array('label' => 'Repeat Password')));
        $builder->add('Register', SubmitType::class);
        
    }
    
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContractingUser'
        ));
    }
    
    public function getName() {
         return 'contracting_user';
    }

}

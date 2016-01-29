<?php
// src/AppBundle/Form/Type/EconomicUserRegistrationType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EconomicUserRegistrationType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new EconomicUserType(),array('label'=>false));
        $builder->add('terms','checkbox',array('property_path' => 'termsAccepted'));
        $builder->add('Register', 'submit');
    }

    public function getName()
    {
        return 'economic_user_registration';
    }

  
}


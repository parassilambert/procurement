<?php
// src/AppBundle/Form/Type/EconomicUserType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EconomicUserType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('companyName', 'text',array('label'=>false));
        $builder->add('taxCountry', 'country',array('label'=>false));
        $builder->add('companyRegistrationNumber', 'text',array('label'=>false));
        $builder->add('taxId', 'text',array('label'=>false));
        $builder->add('email', 'email',array('label'=>false));
        $builder->add('firstName', 'text',array('label'=>false));
        $builder->add('lastName', 'text',array('label'=>false));
        $builder->add('address', 'textarea',array('label'=>false));
        $builder->add('phoneNumber', 'text',array('label'=>false));
        $builder->add('portfolios', 'collection', array('type' => new PortfolioType(),'allow_add' => true,'by_reference' => false,'allow_delete' => true,'label'=>false));
        $builder->add('username', 'text',array('label'=>false));
        $builder->add('password', 'repeated', array('first_name'  => 'Create_password','second_name' => 'Confirm_password','type'=> 'password','label'=>false));
     }
    
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EconomicUser'
        ));
    }
     public function getName()
    {
        return 'user';
    }
}

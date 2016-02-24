<?php
// src/AppBundle/Form/Type/EconomicUserType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class EconomicUserType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder->add('companyName', TextType::class,array('label'=>false));
        $builder->add('taxCountry', CountryType::class,array('label'=>false));
        $builder->add('companyRegistrationNumber', TextType::class,array('label'=>false));
        $builder->add('taxId', TextType::class,array('label'=>false));
        $builder->add('email', EmailType::class,array('label'=>false));
        $builder->add('firstName', TextType::class,array('label'=>false));
        $builder->add('lastName', TextType::class,array('label'=>false));
        $builder->add('address', TextareaType::class,array('label'=>false));
        $builder->add('phoneNumber', TextType::class,array('label'=>false));
        $builder->add('portfolios', CollectionType::class, array('entry_type' => PortfolioType::class,'allow_add' => true,'by_reference' => false,'allow_delete' => true,'label'=>false));
        $builder->add('username', TextType::class,array('label'=>false));
        $builder->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,'first_options' => array('label' => 'Password'),'second_options' => array('label' => 'Repeat Password')));
        $builder->add('Register', SubmitType::class);     }
    
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EconomicUser'
        ));
    }
     public function getName()
    {
        return 'economic_user';
    }
}

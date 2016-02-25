<?php


namespace AppBundle\Form\Type;

/**
 * Description of ContractOfficerType
 *
 * @author lambert
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContractOfficerType extends AbstractType{
      public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('adminUser', EntityType::class,  array(
       'class' => 'AppBundle:ContractingUser',
       'query_builder' => function(\AppBundle\Repository\ContractingUserRepository $er) {
       return $er->createQueryBuilder('u')
                ->groupBy('u.id')
                ->orderBy('u.firstname', 'ASC');
         },
        'label' => false        
        )
       );
       $builder->add('Save', SubmitType::class);
    }
     public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContractOfficer'
        ));
    }
    
    public function getName() {
         return 'contract_officer_entity';
    }

}

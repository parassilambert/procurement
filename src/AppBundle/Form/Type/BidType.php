<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of BidType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BidType extends AbstractType{
    protected $contract;

    public function __construct (\AppBundle\Entity\Contract $contract)
     {
       $this->contract = $contract;
     }
    public function buildForm(FormBuilderInterface $builder, array $options)
       {
         $contract= $this->contract;
         $builder->add('bidEvaluations', 'collection', array('type' => new BidEvaluationType($contract),'allow_add'    => true,'by_reference' => false,'allow_delete' => true,'label'=>false));
         $builder->add('Save','submit', array('label' => 'Save'));
       }
       
    public function configureOptions(OptionsResolver $resolver)
       {
         $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\Bid',
             ));
       }
       
    public function getName() {
        return 'bid';
    }
}

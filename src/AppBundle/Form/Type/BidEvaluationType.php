<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of BidScoreType
 *
 * @author lambert
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class BidEvaluationType extends AbstractType{
    protected $id;

    public function __construct (\AppBundle\Entity\Contract $contract)
     {
       $this->id = $contract->getId();
     }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
       {
         $id= $this->id;
         $builder->add('awardCreteria', 'entity', array(
                       'class' => 'AppBundle:ContractEvaluation',
                       'query_builder' => function (EntityRepository $er) use($id) {
                       return $er->createQueryBuilder('c')
                                 ->orderBy('c.creterion', 'ASC')
                                 ->where('c.contract = :id')
                                 ->setParameter("id", $id);
                           },
                      ));
         $builder->add('score', 'integer', array('label' => 'Score:'));
         $builder->add('Save','submit', array('label' => 'Save'));

       }
       
    public function configureOptions(OptionsResolver $resolver)
       {
         $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\BidEvaluation',
             ));
       }
       
    public function getName() {
        return 'bid_evaluation';
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\EventListener;

/**
 * Description of ContractNoticeStep3Subscriber
 *
 * @author lambert
 */
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContractNoticeStep3Subscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
       return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }
    
    public function preSetData(FormEvent $event)
     {
        $form = $event->getForm();
        $data = $event->getData();
        $form->add('guaranteeCondition', 'textarea');
        $form->add('financialCondition', 'textarea');
        $form->add('legalCondition', 'textarea');
        $form->add('hasOtherCondition', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        if($data->getHasOtherCondition()=="No") {
           $form->add('otherCondition', 'textarea',array('attr' => array('disabled' => true)));
        }else{
           $form->add('otherCondition', 'textarea');
        }
        $form->add('eligibility', 'textarea');
        $form->add('financialEvaluation', 'textarea');
        $form->add('financialMinLevel', 'textarea');
        $form->add('technicalEvaluation', 'textarea');
        $form->add('technicalMinLevel', 'textarea');
        $form->add('save', 'submit', array('label' => 'Save'));
        $form->add('nextStep','submit', array('label' => 'Next'));
        $form->add('previousStep', 'submit',array('validation_groups' => false,'label' => 'Previous'));
    }

}

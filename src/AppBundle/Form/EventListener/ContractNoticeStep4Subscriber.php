<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\EventListener;

/**
 * Description of ContractNoticeStep4Subscriber
 *
 * @author lambert
 */

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Form\Type\ContractEvaluationType;

class ContractNoticeStep4Subscriber implements EventSubscriberInterface {
    
    public static function getSubscribedEvents() {
       return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }
    public function preSetData(FormEvent $event)
     {
        $form = $event->getForm();
        $form->add('awardCreteriaType', 'choice',array('choices' => array(
                       'The most economically advantegeous tender'   => 'The most economically advantegeous tender',
                       'Lowest price'    => 'Lowest price'),
                       'expanded' => true));
        $form->add('contractEvaluation', 'collection', array('type' => new ContractEvaluationType(),'allow_add' => true,'by_reference' => false,'allow_delete' => true,'label'=>false));
        $form->add('referenceNumber','text',array('attr' => array('disabled' => true)));
        $form->add('isPayableDocument', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $form->add('price', 'money',array('required' => false));
        $form->add('paymentCurrency', 'currency',array('required' => false));
        $form->add('paymentTerms', 'textarea',array('required' => false));
        $form->add('closingDate', 'datetime');
        $form->add('openingDate', 'datetime');
        $form->add('openingVenue', 'textarea');
        $form->add('isPersonAuthorised', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No'    => 'No'),
                       'expanded' => true));
        $form->add('authorisedPerson', 'textarea');
        $form->add('save', 'submit', array('label' => 'Save'));
        $form->add('previousStep', 'submit',array('validation_groups' => false,'label' => 'Previous'));
        $form->add('nextStep','submit', array('label' => 'Next'));
     }
}

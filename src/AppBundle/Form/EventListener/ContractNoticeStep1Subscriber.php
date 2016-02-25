<?php

namespace AppBundle\Form\EventListener;

/**
 * Description of ContractStep1Subscriber
 *
 * @author lambert
 */

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContractNoticeStep1Subscriber implements EventSubscriberInterface {
    
    public static function getSubscribedEvents()
     {
      return array(FormEvents::PRE_SET_DATA => 'preSetData');
     }
     
    public function preSetData(FormEvent $event)
     {
        $form = $event->getForm();
        $form->add('contractType', 'choice',array('choices' => array(
                       'Works'    => 'Works',
                       'Services' => 'Services',
                       'Supplies' => 'Supplies'),
                       'expanded' => true));
        $form->add('procedureType', 'choice',array('choices' => array(
                       'Open'       => 'Open',
                       'Restricted' => 'Restricted'),
                       'expanded' => true));
        $form->add('save', 'submit', array('label' => 'Save'));
        $form->add('nextStep','submit', array('label' => 'Next'));
     } 
}

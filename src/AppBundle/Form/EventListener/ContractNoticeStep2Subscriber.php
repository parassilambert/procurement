<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\EventListener;

/**
 * Description of ContractNoticeStep2Subscriber
 *
 * @author lambert
 */
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContractNoticeStep2Subscriber implements EventSubscriberInterface{
    public static function getSubscribedEvents() {
      return array(FormEvents::PRE_SET_DATA => 'preSetData',  FormEvents::POST_SUBMIT => 'postSubmitData');
    }
    public function preSetData(FormEvent $event)
     {
        $form = $event->getForm(); 
        $data = $event->getData();
        $form->add('title', 'text');
        $form->add('description','textarea');
        $form->add('hasLotDivision', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No' => 'No'),
                       'expanded' => true));
        if($data->getHasLotDivision()=="No") {
           $form->add('lot','integer',array('attr' => array('disabled' => true)));
           $form->add('lotSubmissionType','choice',array('choices' => array(
                               'One lot only'   => 'One lot only',
                               'One or more lots' => 'One or more lots',
                               'All lots' => 'All lots'),
                               'expanded' => true,'disabled' => true));
        }  else {
           $form->add('lot','integer');
           $form->add('lotSubmissionType','choice',array('choices' => array(
                               'One lot only'   => 'One lot only',
                               'One or more lots' => 'One or more lots',
                               'All lots' => 'All lots'),
                               'expanded' => true));    
        }
        $form->add('hasVariants', 'choice',array('choices' => array(
                       'Yes'   => 'Yes',
                       'No' => 'No'),
                       'expanded' => true));
        $form->add('scope', 'textarea');
        $form->add('estimatedValueType', 'choice',array('choices' => array(
                       'Exact'   => 'Exact',
                       'Range' => 'Range'),
                       'expanded' => true));
        if ($data->getEstimatedValueType()=="Exact") {
            $form->add('rangeStartValue', 'money',array('attr' => array('disabled' => true)));
            $form->add('rangeEndValue', 'money',array('attr' => array('disabled' => true)));
            $form->add('rangeCurrency', 'currency',array('placeholder' => 'Choose a currency','attr' => array('disabled' => true)));   
        }  else {
            $form->add('rangeStartValue', 'money');
            $form->add('rangeEndValue', 'money');
            $form->add('rangeCurrency', 'currency',array('placeholder' => 'Choose a currency')); 
        }
        if ($data->getEstimatedValueType()=="Range") {
            $form->add('exactValue', 'money',array('attr' => array('disabled' => true)));
            $form->add('exactCurrency', 'currency',array('placeholder' => 'Choose a currency','attr' => array('disabled' => true)));  
        }  else {
            $form->add('exactValue', 'money');
            $form->add('exactCurrency', 'currency',array('placeholder' => 'Choose a currency'));
        }
        $form->add('contractDurationType', 'choice',array('choices' => array(
                       'Exact'   => 'Exact',
                       'Range' => 'Range'),
                       'expanded' => true));
        if ($data->getContractDurationType()=="Exact") {
            $form->add('rangeStartDate','date',array('disabled' => true));
            $form->add('rangeEndDate', 'date',array('disabled' => true));
        }  else {
            $form->add('rangeStartDate','date');
            $form->add('rangeEndDate', 'date');
        }
        if ($data->getContractDurationType()=="Range") {
            $form->add('exactDurationType', 'choice',array('choices' => array(
                       'In months'   => 'In months',
                       'In days' => 'In days'),
                       'expanded' => true,'disabled' => true));
            $form->add('exactDuration', 'integer',array('attr' => array('disabled' => true)));
        }  else {
            $form->add('exactDurationType', 'choice',array('choices' => array(
                       'In months'   => 'In months',
                       'In days' => 'In days'),
                       'expanded' => true));
            $form->add('exactDuration', 'integer');
        }
        $form->add('save', 'submit', array('label' => 'Save'));
        $form->add('nextStep','submit', array('label' => 'Next'));
        $form->add('previousStep', 'submit',array('validation_groups' => false,'label' => 'Previous'));
     }
     
     public function postSubmitData(){
         
     }
}

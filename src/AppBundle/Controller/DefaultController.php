<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Payment;
class DefaultController extends Controller{
    
    /**
     * @Route("/paymentgateway", name="payment_gateway")
     * @Template()
     */    
 public function recievePaymentAction(Request $request){
     if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $payment = new Payment();
      
        $payment->setBusinessNumber($request->request->get('business_number'));
        $payment->setTransactionReference($request->request->get('transaction_reference'));
        $payment->setInternalTransactionId($request->request->get('internal_transaction_id'));
        $payment->setTransactionTimestamp($request->request->get('transaction_timestamp'));
        $payment->setTransactionType($request->request->get('transaction_type'));
        $payment->setAccountNumber($request->request->get('account_number'));
        $payment->setSenderPhone($request->request->get('sender_phone'));
        $payment->setFirstName($request->request->get('first_name'));
        $payment->setMiddleName($request->request->get('middle_name'));
        $payment->setLastName($request->request->get('last_name'));
        $payment->setAmount($request->request->get('amount'));
        $payment->setCurrency($request->request->get('currency'));
        $payment->setSignature($request->request->get('signature'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($payment);
        $em->flush();
     }
    }
}


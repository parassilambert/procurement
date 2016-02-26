<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Audit;
class DefaultController extends Controller{
    
    /**
     * @Route("/paymentgateway", name="payment_gateway")
     */    
 public function recievePaymentAction(Request $request){
     if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $payment = new Payment();
        $data = json_decode($request->getContent(), true);
        
        $base_string = $data['account_number']."=account_number&".
                 $data['amount']."=amount&".$data['business_number']."=business_number&".
                 $data['currency']."=currency&".$data['first_name']."=first_name&".
                 $data['internal_transaction_id']."=internal_transaction_id&".$data['last_name']."=last_name&".$data['middle_name']."=middle_name&".
                 $data['sender_phone']."=sender_phone&".$data['service_name']."=service_name&".$data['transaction_reference']."=transaction_reference&".
                 $data['transaction_timestamp']."=transaction_timestamp&".$data['transaction_type']."=transaction_type";
   
        $hashed_value =  (hash_hmac("sha1", $base_string, "810c06caa57be23b9006e5e4499d1001f423e149"));
        $hashed_expected = $data['signature'];
        if (self::hash_compareAction($hashed_value, $hashed_expected)) {
        $payment->setBusinessNumber($data['business_number']);
        $payment->setTransactionReference($data['transaction_reference']);
        $payment->setInternalTransactionId($data['internal_transaction_id']);
        $payment->setTransactionTimestamp($data['transaction_timestamp']);
        $payment->setTransactionType($data['transaction_type']);
        $payment->setAccountNumber($data['account_number']);
        $payment->setSenderPhone($data['sender_phone']);
        $payment->setFirstName($data['first_name']);
        $payment->setMiddleName($data['middle_name']);
        $payment->setLastName($data['last_name']);
        $payment->setAmount($data['amount']);
        $payment->setCurrency($data['currency']);
        $payment->setSignature($data['signature']);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($payment);
        $em->flush();
        return $response = new Response("Hashes match!"); 
       }else{
           
        //Audit
        $audit = new Audit();
        $audit->setUsername($data['sender_phone']);
        $audit->setName($data['first_name']." ".$data['last_name']);
        $audit->setFunctionType("Payment");
        $audit->setEventType("Hashes do not match!");
        $em = $this->getDoctrine()->getManager();
        $em->persist($audit);
        $em->flush();
       return $response = new Response("Hashes do not match!"." " . "Base_String:".$base_string." " ."Generated signature:".$hashed_value); 
      }
    }
 }
     function hash_compareAction($a, $b) {
        if (!is_string($a) || !is_string($b)) {
            return false;
        }
       
        $len = strlen($a);
        if ($len !== strlen($b)) {
            return false;
        }

        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $status === 0;
    } 
  
}

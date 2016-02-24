<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Payment;
class DefaultController extends Controller{
    
    /**
     * @Route("/paymentgateway", name="payment_gateway")
     * @Template()
     */    
 public function recievePaymentAction(Request $request){
     if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $payment = new Payment();
        $data = json_decode($request->getContent(), true);
        $hashed_value= base64_encode(hash_hmac("sha1", $request->getContent(), "467b0802b92753a97df396b73f65e289d612d2c9", true));
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
       }else{
           echo "hashes do not match!";
           echo  "Expected hashed=".$hashed_expected;
           echo  "Hashed value=".$hashed_value;
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

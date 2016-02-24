<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Security\Authorization\Voter;

/**
 * Description of BidVoter
 *
 * @author lambert
 */
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use AppBundle\Entity\EconomicUser;
use AppBundle\Entity\Tender;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TenderVoter extends Voter{
    
     const EDIT = 'edit';
   
    protected function supports($attribute, $subject) {
        // if the attribute isn't one we support, return false
       if (!in_array($attribute, array(self::EDIT))) {
            return false;
        }
        // only vote on Post objects inside this voter
       if (!$subject instanceof Tender) {
            return false;
        }
            return true;
    }

    protected function voteOnAttribute($attribute, $subject, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token) {
            $user = $token->getUser();
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
           return false;
        }
        // you know $subject is a Post object, thanks to supports
        /** @var Tender $tender */
          $tender = $subject;
          switch($attribute) {
            case self::EDIT:
                return $this->canEdit($tender, $user);
            }
        throw new \LogicException('This code should not be reached!');
    }
    
    private function canEdit(Tender $tender, EconomicUser $user)
         {
           // this assumes that the data object has a getOwner() method
           // to get the entity of the user who owns this data object
         return $user === $tender->getBid()->getEconomicUser();
        }
}

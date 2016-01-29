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
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use AppBundle\Entity\EconomicUser;
use Symfony\Component\Security\Core\User\UserInterface;

class TenderVoter extends AbstractVoter{
    
     const EDIT = 'edit';
    protected function getSupportedAttributes() {
        return array(self::EDIT);
    }

    protected function getSupportedClasses() {
        return array('AppBundle\Entity\Tender');
    }

    protected function isGranted($attribute, $tender, $user = null) {
          // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }
        // double-check that the User object is the expected entity (this
        // only happens when you did not configure the security system properly)
        if (!$user instanceof EconomicUser) {
            throw new \LogicException('The user is somehow not our EconomicUser class!');
        }
                switch($attribute) {
                    case self::EDIT:
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
                       if ($user->getId() === $tender->getBid()->getEconomicUser()->getId()) {
                           return true;
                        }
                        break;
                }
                    return false;
    }
}

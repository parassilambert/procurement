<?php

// src/AppBundle/Form/Model/ContractingRegistration.php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\ContractingUser;

class ContractingUserRegistration {
    /**
     * @Assert\Type(type="AppBundle\Entity\ContractingUser")
     * @Assert\Valid()
     */
    protected $user;
    
    public function setUser(ContractingUser $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
   
}

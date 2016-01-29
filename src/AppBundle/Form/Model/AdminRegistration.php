<?php

// src/AppBundle/Form/Model/AdminRegistration.php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\AdminUser;

class AdminRegistration {
    /**
     * @Assert\Type(type="AppBundle\Entity\AdminUser")
     * @Assert\Valid()
     */
    protected $user;
    
    public function setUser(AdminUser $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
   
}

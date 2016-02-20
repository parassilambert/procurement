<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Model;

/**
 * Description of AdminUserRegistration
 *
 * @author lambert
 */
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\AdminUser;

class AdminUserRegistration {
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

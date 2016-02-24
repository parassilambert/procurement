<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of AdminUser
 *
 * @author lambert
 */
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="adminuser")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdminUserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AdminUser implements UserInterface,  \Serializable{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string",length=25,unique=true)
     */
    protected $username;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    /**
     * @ORM\Column(type="string",length=64)
     */
    protected $password;
    
    /**
     * @ORM\Column(type="string",length=25)
     */    
    protected $firstname;
    
    /**
     * @ORM\Column(type="string",length=25)
     */    
    protected $lastname;
    
    /**
     * @ORM\Column(type="string",length=60,unique=true)
     */
    protected $email;
    
    /**
     * @ORM\Column(name="is_active",type="boolean")
     */
    protected $isActive;
    
    /**
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;
    
     public function __construct() {
        $this->isActive = true;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return array("ROLE_ADMIN");
    }

    public function getSalt() {
        return null;
    }

    public function getUsername() {
        return $this->username;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
    /**
     * @see \Serializable::serialize()
     */

    public function serialize() {
        return serialize(array($this->id,$this->username,$this->password));
    }
    
    /**
     * @see \Serializable::unserialize()
     */

    public function unserialize($serialized) {
        list($this->id,$this->username,$this->password) = unserialize($serialized);
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        $this->createdAt = new \DateTime();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set id
     *
     * @param string $id
     *
     * @return AdminUser
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Set username
     *
     * @param string $username
     *
     * @return AdminUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return AdminUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return AdminUser
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return AdminUser
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return AdminUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return AdminUser
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

 
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

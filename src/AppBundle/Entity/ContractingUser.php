<?php

namespace AppBundle\Entity;

/**
 * Description of ContractingUser
 *
 * @author lambert
 */
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="contractinguser")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractingUserRepository")
 * @ORM\HasLifecycleCallBacks()
 */

class ContractingUser implements UserInterface,  \Serializable{
    
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

    /**
     * @ORM\OneToMany(targetEntity="Contract",mappedBy="contractingUser")
     */
    protected $contracts;

    /**
     * @ORM\OneToMany(targetEntity="ContractOfficer",mappedBy="contractingUser")
     */
    protected $associatedContracts;
    
    public function __construct() {
        $this->isActive = true;
        $this->contracts = new ArrayCollection();
        $this->associatedContracts = new ArrayCollection();
    }

    public function eraseCredentials() {
        
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
    public function getRoles() {
        return array("ROLE_CONTRACTING");
    }

    public function getSalt() {
        return null;
    }

    public function getUsername() {
        return $this->username;
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
     * @return ContractingUser
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
     * @return ContractingUser
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
     * @return ContractingUser
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
     * @return ContractingUser
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
     * @return ContractingUser
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
     * @return ContractingUser
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
     * @return ContractingUser
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContractingUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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

    /**
     * Add contract
     *
     * @param \AppBundle\Entity\Contract $contract
     *
     * @return ContractingUser
     */
    public function addContract(\AppBundle\Entity\Contract $contract)
    {
        $this->contracts[] = $contract;

        return $this;
    }

    /**
     * Remove contract
     *
     * @param \AppBundle\Entity\Contract $contract
     */
    public function removeContract(\AppBundle\Entity\Contract $contract)
    {
        $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContracts()
    {
        return $this->contracts;
    }


    /**
     * Add associatedContract
     *
     * @param \AppBundle\Entity\ContractOfficer $associatedContract
     *
     * @return ContractingUser
     */
    public function addAssociatedContract(\AppBundle\Entity\ContractOfficer $associatedContract)
    {
        $this->associatedContracts[] = $associatedContract;

        return $this;
    }

    /**
     * Remove associatedContract
     *
     * @param \AppBundle\Entity\ContractOfficer $associatedContract
     */
    public function removeAssociatedContract(\AppBundle\Entity\ContractOfficer $associatedContract)
    {
        $this->associatedContracts->removeElement($associatedContract);
    }

    /**
     * Get associatedContracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssociatedContracts()
    {
        return $this->associatedContracts;
    }
    
    public function __toString() {
        return $this->firstname." ".$this->lastname;
    }
}

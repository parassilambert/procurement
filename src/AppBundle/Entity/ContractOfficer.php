<?php

namespace AppBundle\Entity;

/**
 * Description of ContractOfficer
 *
 * @author lambert
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contractofficer")
 * @ORM\HasLifecycleCallBacks()
 */
class ContractOfficer {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contract",inversedBy="associatedOfficers")
     * @ORM\JoinColumn(name="contract",referencedColumnName="id")
     */
    protected $contract;
    
     /**
     * @ORM\ManyToOne(targetEntity="ContractingUser",inversedBy="associatedContracts")
     * @ORM\JoinColumn(name="contract_officer",referencedColumnName="id")
     */
    protected $contractingUser;
    
    /**
     * @ORM\Column(type="string",length=60)
     */
    protected $permission;
    /**
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        $this->createdAt=new \DateTime();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContractOfficer
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
     * Set contract
     *
     * @param \AppBundle\Entity\Contract $contract
     *
     * @return ContractOfficer
     */
    public function setContract(\AppBundle\Entity\Contract $contract = null)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract
     *
     * @return \AppBundle\Entity\Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set contractingUser
     *
     * @param \AppBundle\Entity\ContractingUser $contractingUser
     *
     * @return ContractOfficer
     */
    public function setContractingUser(\AppBundle\Entity\ContractingUser $contractingUser = null)
    {
        $this->contractingUser = $contractingUser;

        return $this;
    }

    /**
     * Get contractingUser
     *
     * @return \AppBundle\Entity\ContractingUser
     */
    public function getContractingUser()
    {
        return $this->contractingUser;
    }

    /**
     * Set permission
     *
     * @param string $permission
     *
     * @return ContractOfficer
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }
}

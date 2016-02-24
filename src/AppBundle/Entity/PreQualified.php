<?php

namespace AppBundle\Entity;

/**
 * Description of PreQualified
 *
 * @author lambert
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="prequalified")
 * @ORM\HasLifecycleCallBacks()
 */
class PreQualified {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contract",inversedBy="preQualified")
     * @ORM\JoinColumn(name="contract",referencedColumnName="id")
     */
    protected $contract;
    
    /**
     * @ORM\ManyToOne(targetEntity="EconomicUser",inversedBy="preQualified")
     * @ORM\JoinColumn(name="company_name",referencedColumnName="id")
     */
    protected $companyName;
    
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
     * @return PreQualified
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
     * @return PreQualified
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
     * Set companyName
     *
     * @param \AppBundle\Entity\EconomicUser $companyName
     *
     * @return PreQualified
     */
    public function setCompanyName(\AppBundle\Entity\EconomicUser $companyName = null)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return \AppBundle\Entity\EconomicUser
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }
}

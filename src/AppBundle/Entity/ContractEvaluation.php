<?php

namespace AppBundle\Entity;

/**
 * Description of ContractEvaluation
 *
 * @author lambert
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="contractevaluation")
 * @ORM\HasLifecycleCallBacks()
 */
class ContractEvaluation {
   
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contract", inversedBy="contractEvaluation")
     * @ORM\JoinColumn(name="contract", referencedColumnName="id")
     */
    protected $contract;
    
    /**
     * @ORM\Column(type="string",length=255)
     */
    protected $creterion;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $weighting;
    
    /**
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;
    
    /**
     * @ORM\OneToMany(targetEntity="BidEvaluation", mappedBy="awardCreteria")
     */
    protected $bidEvaluations;
    
    public function __construct() {
        $this->bidEvaluations = new ArrayCollection();
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(){
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
     * Set creterion
     *
     * @param string $creterion
     *
     * @return ContractEvaluation
     */
    public function setCreterion($creterion)
    {
        $this->creterion = $creterion;

        return $this;
    }

    /**
     * Get creterion
     *
     * @return string
     */
    public function getCreterion()
    {
        return $this->creterion;
    }

    /**
     * Set weighting
     *
     * @param integer $weighting
     *
     * @return ContractEvaluation
     */
    public function setWeighting($weighting)
    {
        $this->weighting = $weighting;

        return $this;
    }

    /**
     * Get weighting
     *
     * @return integer
     */
    public function getWeighting()
    {
        return $this->weighting;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContractEvaluation
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
     * @return ContractEvaluation
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

    public function addContract(\AppBundle\Entity\Contract $contract) {
        if (!$this->contract->contains($contract)) {
             $this->contract->add($contract);
         }
    }
    
    /**
     * Add bidEvaluation
     *
     * @param \AppBundle\Entity\BidEvaluation $bidEvaluation
     *
     * @return ContractEvaluation
     */
    public function addBidEvaluation(\AppBundle\Entity\BidEvaluation $bidEvaluation)
    {
        $bidEvaluation->setBid($this);
        $this->bidEvaluations->add($bidEvaluation);
    }

    /**
     * Remove bidEvaluation
     *
     * @param \AppBundle\Entity\BidEvaluation $bidEvaluation
     */
    public function removeBidEvaluation(\AppBundle\Entity\BidEvaluation $bidEvaluation)
    {
        $this->bidEvaluations->removeElement($bidEvaluation);
    }

    /**
     * Get bidEvaluations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBidEvaluations()
    {
        return $this->bidEvaluations;
    }
    
    public function __toString() {
        
        return $this->creterion."-".$this->weighting;
    }
}

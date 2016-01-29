<?php

namespace AppBundle\Entity;

/**
 * Description of Bid
 *
 * @author lambert
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="bid")
 * @ORM\HasLifecycleCallBacks()
 */
class Bid {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contract", inversedBy="bids")
     * @ORM\JoinColumn(name="contract",referencedColumnName="id")
     */
    protected $contract;
    
    /**
     * @ORM\ManyToOne(targetEntity="EconomicUser", inversedBy="bids")
     * @ORM\JoinColumn(name="economic_user",referencedColumnName="id")
     */
    protected $economicUser;
    
    /**
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;
    
    /**
     * @ORM\OneToMany(targetEntity="Tender", mappedBy="bid")
     */
    protected $tenders;
    
    /**
     * @ORM\OneToMany(targetEntity="BidEvaluation", mappedBy="bid")
     */
    protected $bidEvaluations;
    
    
    
    public function __construct() {
        $this->tenders = new ArrayCollection();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Bid
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
     * @return Bid
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
     * Set economicUser
     *
     * @param \AppBundle\Entity\EconomicUser $economicUser
     *
     * @return Bid
     */
    public function setEconomicUser(\AppBundle\Entity\EconomicUser $economicUser = null)
    {
        $this->economicUser = $economicUser;

        return $this;
    }

    /**
     * Get economicUser
     *
     * @return \AppBundle\Entity\EconomicUser
     */
    public function getEconomicUser()
    {
        return $this->economicUser;
    }

    /**
     * Add tender
     *
     * @param \AppBundle\Entity\Tender $tender
     *
     * @return Bid
     */
    public function addTender(\AppBundle\Entity\Tender $tender)
    {
        $this->tenders[] = $tender;

        return $this;
    }

    /**
     * Remove tender
     *
     * @param \AppBundle\Entity\Tender $tender
     */
    public function removeTender(\AppBundle\Entity\Tender $tender)
    {
        $this->tenders->removeElement($tender);
    }

    /**
     * Get tenders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTenders()
    {
        return $this->tenders;
    }

    /**
     * Add bidEvaluation
     *
     * @param \AppBundle\Entity\BidEvaluation $bidEvaluation
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
}

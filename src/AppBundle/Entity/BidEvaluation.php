<?php

namespace AppBundle\Entity;

/**
 * Description of BidEvaluation
 *
 * @author lambert
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bidevaluation")
 * @ORM\HasLifecycleCallBacks()
 */
class BidEvaluation {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bid",inversedBy="bidEvaluations")
     * @ORM\JoinColumn(name="bid", referencedColumnName="id")
     */
    protected $bid;
    
    /**
     * @ORM\ManyToOne(targetEntity="ContractEvaluation", inversedBy="bidEvaluations")
     * @ORM\JoinColumn(name="award_creteria", referencedColumnName="id")
     */
    protected $awardCreteria;

    /**
     * @ORM\Column(type="integer")
     */
    protected $score;
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    
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
     * Set score
     *
     * @param integer $score
     *
     * @return BidEvaluation
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BidEvaluation
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
     * Set bid
     *
     * @param \AppBundle\Entity\Bid $bid
     *
     * @return BidEvaluation
     */
    public function setBid(\AppBundle\Entity\Bid $bid = null)
    {
        $this->bid = $bid;

        return $this;
    }

    /**
     * Get bid
     *
     * @return \AppBundle\Entity\Bid
     */
    public function getBid()
    {
        return $this->bid;
    }
    
    public function addBid(\AppBundle\Entity\Bid $bid) {
        if (!$this->bid->contains($bid)) {
             $this->bid->add($bid);
         }
    }

    /**
     * Set awardCreteria
     *
     * @param \AppBundle\Entity\ContractEvaluation $awardCreteria
     *
     * @return BidEvaluation
     */
    public function setAwardCreteria(\AppBundle\Entity\ContractEvaluation $awardCreteria = null)
    {
        $this->awardCreteria = $awardCreteria;

        return $this;
    }

    /**
     * Get awardCreteria
     *
     * @return \AppBundle\Entity\ContractEvaluation
     */
    public function getAwardCreteria()
    {
        return $this->awardCreteria;
    }
    
     public function addAwardCreteria(\AppBundle\Entity\ContractEvaluation $awardCreteria) {
        if (!$this->awardCreteria->contains($awardCreteria)) {
             $this->awardCreteria->add($awardCreteria);
         }
    }
}

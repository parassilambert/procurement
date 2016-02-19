<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of Payment
 *
 * @author lambert
 */

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="payment")
 * @ORM\HasLifecycleCallBacks()
 */
class Payment {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
         
    /**
     * @ORM\Column(name="business_number",type="integer")
     * @Assert\NotBlank()
     */
    protected $businessNumber;
    
    /**
     * @ORM\Column(name="transaction_reference",type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $transactionReference;
    
    /**
     * @ORM\Column(name="internal_transaction_id",type="integer")
     * @Assert\NotBlank()
     */
    protected $internalTransactionId;
    
    /**
     * @ORM\Column(name="transaction_timestamp",type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $transactionTimestamp;
    
    /**
     * @ORM\Column(name="transaction_type",type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $transactionType;
    
    /**
     * @ORM\Column(name="account_number",type="integer",nullable=true)
     * @Assert\NotBlank()
     */
    protected $accountNumber;
    
    /**
     * @ORM\Column(name="sender_phone",type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $senderPhone;
    
    /**
     * @ORM\Column(name="first_name",type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $firstName;
    
    /**
     * @ORM\Column(name="middle_name",type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $middleName;
    
    /**
     * @ORM\Column(name="last_name",type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $lastName;
    
    /**
     * @ORM\Column(type="decimal",scale=2,nullable=false)
     * @Assert\NotBlank()
     */
    protected $amount;
    
    /**
     * @ORM\Column(type="string",length=25,nullable=false)
     * @Assert\NotBlank()
     */
    protected $currency;
    
    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     * @Assert\NotBlank()
     */
    protected $signature;
    
    /**
     * @ORM\Column(name="created_at",type="datetime",length=255,nullable=false)
     * @Assert\NotBlank()
     */
    protected $createdAt;
    
    /**
     * @ORM\Column(name="updated_at",type="datetime",length=255,nullable=true)
     * @Assert\NotBlank()
     */
    protected $updatedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contract", inversedBy="payments")
     * @ORM\JoinColumn(name="contract",referencedColumnName="id")
     */
    protected $contract;
    
     /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        $this->createdAt = new \DateTime();
    }
        
    /**
     * @ORM\PreUpdate
     */
    public function setUpdateAtValue(){
        $this->updatedAt = new \DateTime();
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
     * Set businessNumber
     *
     * @param integer $businessNumber
     *
     * @return Payment
     */
    public function setBusinessNumber($businessNumber)
    {
        $this->businessNumber = $businessNumber;

        return $this;
    }

    /**
     * Get businessNumber
     *
     * @return integer
     */
    public function getBusinessNumber()
    {
        return $this->businessNumber;
    }

    /**
     * Set transactionReference
     *
     * @param string $transactionReference
     *
     * @return Payment
     */
    public function setTransactionReference($transactionReference)
    {
        $this->transactionReference = $transactionReference;

        return $this;
    }

    /**
     * Get transactionReference
     *
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->transactionReference;
    }

    /**
     * Set internalTransactionId
     *
     * @param integer $internalTransactionId
     *
     * @return Payment
     */
    public function setInternalTransactionId($internalTransactionId)
    {
        $this->internalTransactionId = $internalTransactionId;

        return $this;
    }

    /**
     * Get internalTransactionId
     *
     * @return integer
     */
    public function getInternalTransactionId()
    {
        return $this->internalTransactionId;
    }

   
    /**
     * Set transactionType
     *
     * @param string $transactionType
     *
     * @return Payment
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get transactionType
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set accountNumber
     *
     * @param integer $accountNumber
     *
     * @return Payment
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return integer
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set senderPhone
     *
     * @param string $senderPhone
     *
     * @return Payment
     */
    public function setSenderPhone($senderPhone)
    {
        $this->senderPhone = $senderPhone;

        return $this;
    }

    /**
     * Get senderPhone
     *
     * @return string
     */
    public function getSenderPhone()
    {
        return $this->senderPhone;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Payment
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Payment
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Payment
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Payment
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set signature
     *
     * @param string $signature
     *
     * @return Payment
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Payment
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Payment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set contract
     *
     * @param \AppBundle\Entity\Contract $contract
     *
     * @return Payment
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
     * Set transactionTimestamp
     *
     * @param string $transactionTimestamp
     *
     * @return Payment
     */
    public function setTransactionTimestamp($transactionTimestamp)
    {
        $this->transactionTimestamp = $transactionTimestamp;

        return $this;
    }

    /**
     * Get transactionTimestamp
     *
     * @return string
     */
    public function getTransactionTimestamp()
    {
        return $this->transactionTimestamp;
    }
}

<?php
namespace AppBundle\Entity;

/**
 * Description of Contract
 *
 * @author lambert
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="contract")
 * @ORM\HasLifecycleCallBacks()
 */
class Contract {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="reference_number",type="string",length=60,unique=true)
     * @Assert\NotBlank(groups={"validationStep1"})
     */
    protected $referenceNumber;

    /**
     * @ORM\Column(name="contract_type",type="string",length=25)
     * @Assert\NotBlank(groups={"validationStep1"})
     */
    protected $contractType;
    
    /**
     * @ORM\Column(name="procedure_type",type="string",length=25)
     * @Assert\NotBlank(groups={"validationStep1"})
     */
    protected $procedureType;
    
    /**
     * @ORM\Column(type="string",length=225,nullable=true)
     * @Assert\NotBlank(groups={"validationStep2"})
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string",length=225,nullable=true)
     * @Assert\NotBlank(groups={"validationStep2"})
     */
    protected $description;
    
    /**
     * @ORM\Column(name="has_lot_division",type="string",length=3,nullable=true,options={"default":"No"})
     * @Assert\Choice({"Yes", "No"})
     * @Assert\NotBlank(groups={"validationStep2"})
     */
    protected $hasLotDivision;
    
    /**
     * @ORM\Column(type="integer",nullable=true,options={"unsigned":true})
     */
    protected $lot;
    
    /**
     * @ORM\Column(name="lot_submission_type",type="string",length=25,nullable=true)
     * @Assert\Choice({"One lot only", "One or more lots", "All lots"})
     */
    protected $lotSubmissionType;
    
    /**
     * @ORM\Column(name="has_variants",type="string",length=3,nullable=true,options={"default":"No"})
     * @Assert\Choice({"Yes", "No"})
     * @Assert\NotBlank(groups={"validationStep2"})
     */
    protected $hasVariants;
    
    /**
     * @ORM\Column(type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep2"})
     */
    protected $scope;
    
    /**
     * @ORM\Column(name="estimated_value_type",type="string",length=25,nullable=true)
     * @Assert\Choice({"Exact", "Range"})
     * @Assert\NotBlank(groups={"validationStep2"})
     */
    protected $estimatedValueType;
    
    /**
     * @ORM\Column(name="exact_value",type="decimal",scale=2,nullable=true)
     */
    protected $exactValue;
    
    /**
     * @ORM\Column(name="exact_currency",type="string",length=25,nullable=true)
     */
    protected $exactCurrency;
    
    /**
     * @ORM\Column(name="range_start_value",type="decimal",scale=2,nullable=true)
     */
    protected $rangeStartValue;
    
    /**
     * @ORM\Column(name="range_end_value",type="decimal",scale=2,nullable=true)
     */
    protected $rangeEndValue;
    
    /**
     * @ORM\Column(name="range_currency",type="string",length=25,nullable=true)
     */
    protected $rangeCurrency;
    
    /**
     * @ORM\Column(name="contract_duration_type",type="string",length=25,nullable=true)
     * @Assert\Choice({"Exact", "Range"})
     * @Assert\NotBlank(groups={"validationStep2"})
     */
    protected $contractDurationType;
    
    /**
     * @ORM\Column(name="exact_duration_type",type="string",length=25,nullable=true)
     * @Assert\Choice({"In months", "In days"})
     */
    protected $exactDurationType;
    
    /**
     * @ORM\Column(name="exact_duration",type="integer",nullable=true)
     */
    protected $exactDuration;
    
    /**
     * @ORM\Column(name="range_start_date",type="date",nullable=true)
     */
    protected $rangeStartDate;
    
    /**
     * @ORM\Column(name="range_end_date",type="date",nullable=true)
     */
    protected $rangeEndDate;
    
    /**
     * @ORM\Column(name="guarantee_condition",type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $guaranteeCondition;
    
    /**
     * @ORM\Column(name="financial_condition",type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $financialCondition;
    
    /**
     * @ORM\Column(name="legal_condition",type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $legalCondition;
    
    /**
     * @ORM\Column(name="has_other_condition",type="string",length=3,nullable=true,options={"default":"No"})
     * @Assert\Choice({"Yes", "No"})
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $hasOtherCondition;
    
    /**
     * @ORM\Column(name="other_condition",type="string",length=1000,nullable=true)
     */
    protected $otherCondition;
    
    /**
     * @ORM\Column(type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $eligibility;
    
    /**
     * @ORM\Column(name="financial_evaluation",type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $financialEvaluation;
    
    /**
     * @ORM\Column(name="financial_min_level",type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $financialMinLevel;
    
    /**
     * @ORM\Column(name="technical_evaluation",type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $technicalEvaluation;
    
    /**
     * @ORM\Column(name="technical_min_level",type="string",length=1000,nullable=true)
     * @Assert\NotBlank(groups={"validationStep3"})
     */
    protected $technicalMinLevel;
    
    /**
     * @ORM\Column(name="award_creteria_type",type="string",length=60,nullable=true,options={"default":"Lowest price"})
     * @Assert\Choice({"The most economically advantegeous tender", "Lowest price"})
     * @Assert\NotBlank(groups={"validationStep4"})
     */
    protected $awardCreteriaType;
    
    /**
     * @ORM\Column(name="is_payable_document",type="string",length=3,nullable=true,options={"default":"No"})
     * @Assert\Choice({"Yes", "No"})
     * @Assert\NotBlank(groups={"validationStep4"})
     */
    protected $isPayableDocument;
    
    /**
     * @ORM\Column(type="decimal",scale=2,nullable=true)
     */
    protected $price;
    
    /**
     * @ORM\Column(name="payment_currency",type="string",length=25,nullable=true)
     */
    protected $paymentCurrency;
    
    /**
     * @ORM\Column(name="payment_terms",type="string",length=225,nullable=true)
     */
    protected $paymentTerms;
    
    /**
     * @ORM\Column(name="closing_date",type="datetime",nullable=true)
     * @Assert\NotBlank(groups={"validationStep4"})
     */
    protected $closingDate;
    
    /**
     * @ORM\Column(name="opening_date",type="datetime",nullable=true)
     * @Assert\NotBlank(groups={"validationStep4"})
     */
    protected $openingDate;
    
    /**
     * @ORM\Column(name="opening_venue",type="string",length=100,nullable=true)
     */
    protected $openingVenue;
    
    /**
     * @ORM\Column(name="is_person_authorised",type="string",length=3,nullable=true,options={"default":"No"})
     * @Assert\Choice({"Yes", "No"})
     * @Assert\NotBlank(groups={"validationStep4"})
     */
    protected $isPersonAuthorised;
    
    /**
     * @ORM\Column(name="authorised_person",type="string",length=1000,nullable=true)
     */
    protected $authorisedPerson;
    
    /**
     * @ORM\ManyToOne(targetEntity="AdminUser",inversedBy="contracts")
     * @ORM\JoinColumn(name="created_by",referencedColumnName="id")
     */
    protected $adminUser;
    
    /**
     * @ORM\Column(name="created_at",type="datetime",nullable=true)
     */
    protected $createdAt;
    
    /**
     * @ORM\Column(name="updated_at",type="datetime",nullable=true)
     */
    protected $updatedAt;
    
    /**
     * @ORM\Column(type="string",length=25)
     */
    protected $status;
    
    /**
     * @ORM\OneToMany(targetEntity="ContractOfficer",mappedBy="contract")
     */
    protected $associatedOfficers;
    
    /**
     * @ORM\OneToMany(targetEntity="ContractDocument",mappedBy="contract")
     */
    protected $contractDocuments;
    
    /**
     * @ORM\OneToMany(targetEntity="ContractEvaluation", mappedBy="contract",cascade={"persist"}) 
     */
    protected $contractEvaluation;

    /**
     * @ORM\OneToMany(targetEntity="PreQualified",mappedBy="contract")
     */
    protected $preQualified;
    
    /**
     * @ORM\OneToMany(targetEntity="Bid",mappedBy="contract")
     */
    protected $bids;
    
    /**
     * @ORM\OneToMany(targetEntity="Payment",mappedBy="contract")
     */
    protected $payments;

    public function __construct() {
        $this->associatedOfficers = new ArrayCollection();
        $this->contractDocuments = new ArrayCollection();
        $this->contractEvaluation = new ArrayCollection();
        $this->preQualified = new ArrayCollection();
        $this->bids = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

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
     * Set contractType
     *
     * @param string $contractType
     *
     * @return Contract
     */
    public function setContractType($contractType)
    {
        $this->contractType = $contractType;

        return $this;
    }

    /**
     * Get contractType
     *
     * @return string
     */
    public function getContractType()
    {
        return $this->contractType;
    }
   
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Contract
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Contract
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set hasLotDivision
     *
     * @param string $hasLotDivision
     *
     * @return Contract
     */
    public function setHasLotDivision($hasLotDivision)
    {
        $this->hasLotDivision = $hasLotDivision;

        return $this;
    }

    /**
     * Get hasLotDivision
     *
     * @return string
     */
    public function getHasLotDivision()
    {
        return $this->hasLotDivision;
    }

    /**
     * Set lot
     *
     * @param integer $lot
     *
     * @return Contract
     */
    public function setLot($lot)
    {
        $this->lot = $lot;

        return $this;
    }

    /**
     * Get lot
     *
     * @return integer
     */
    public function getLot()
    {
        return $this->lot;
    }

    /**
     * Set lotSubmissionType
     *
     * @param string $lotSubmissionType
     *
     * @return Contract
     */
    public function setLotSubmissionType($lotSubmissionType)
    {
        $this->lotSubmissionType = $lotSubmissionType;

        return $this;
    }

    /**
     * Get lotSubmissionType
     *
     * @return string
     */
    public function getLotSubmissionType()
    {
        return $this->lotSubmissionType;
    }

    /**
     * Set scope
     *
     * @param string $scope
     *
     * @return Contract
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set estimatedValueType
     *
     * @param string $estimatedValueType
     *
     * @return Contract
     */
    public function setEstimatedValueType($estimatedValueType)
    {
        $this->estimatedValueType = $estimatedValueType;

        return $this;
    }

    /**
     * Get estimatedValueType
     *
     * @return string
     */
    public function getEstimatedValueType()
    {
        return $this->estimatedValueType;
    }

    /**
     * Set exactValue
     *
     * @param string $exactValue
     *
     * @return Contract
     */
    public function setExactValue($exactValue)
    {
        $this->exactValue = $exactValue;

        return $this;
    }

    /**
     * Get exactValue
     *
     * @return string
     */
    public function getExactValue()
    {
        return $this->exactValue;
    }

    /**
     * Set rangeStartValue
     *
     * @param string $rangeStartValue
     *
     * @return Contract
     */
    public function setRangeStartValue($rangeStartValue)
    {
        $this->rangeStartValue = $rangeStartValue;

        return $this;
    }

    /**
     * Get rangeStartValue
     *
     * @return string
     */
    public function getRangeStartValue()
    {
        return $this->rangeStartValue;
    }

    /**
     * Set rangeEndValue
     *
     * @param string $rangeEndValue
     *
     * @return Contract
     */
    public function setRangeEndValue($rangeEndValue)
    {
        $this->rangeEndValue = $rangeEndValue;

        return $this;
    }

    /**
     * Get rangeEndValue
     *
     * @return string
     */
    public function getRangeEndValue()
    {
        return $this->rangeEndValue;
    }

  
    /**
     * Set guaranteeCondition
     *
     * @param string $guaranteeCondition
     *
     * @return Contract
     */
    public function setGuaranteeCondition($guaranteeCondition)
    {
        $this->guaranteeCondition = $guaranteeCondition;

        return $this;
    }

    /**
     * Get guaranteeCondition
     *
     * @return string
     */
    public function getGuaranteeCondition()
    {
        return $this->guaranteeCondition;
    }

    /**
     * Set financialCondition
     *
     * @param string $financialCondition
     *
     * @return Contract
     */
    public function setFinancialCondition($financialCondition)
    {
        $this->financialCondition = $financialCondition;

        return $this;
    }

    /**
     * Get financialCondition
     *
     * @return string
     */
    public function getFinancialCondition()
    {
        return $this->financialCondition;
    }

    /**
     * Set legalCondition
     *
     * @param string $legalCondition
     *
     * @return Contract
     */
    public function setLegalCondition($legalCondition)
    {
        $this->legalCondition = $legalCondition;

        return $this;
    }

    /**
     * Get legalCondition
     *
     * @return string
     */
    public function getLegalCondition()
    {
        return $this->legalCondition;
    }

   
    /**
     * Set eligibility
     *
     * @param string $eligibility
     *
     * @return Contract
     */
    public function setEligibility($eligibility)
    {
        $this->eligibility = $eligibility;

        return $this;
    }

    /**
     * Get eligibility
     *
     * @return string
     */
    public function getEligibility()
    {
        return $this->eligibility;
    }

    /**
     * Set financialEvaluation
     *
     * @param string $financialEvaluation
     *
     * @return Contract
     */
    public function setFinancialEvaluation($financialEvaluation)
    {
        $this->financialEvaluation = $financialEvaluation;

        return $this;
    }

    /**
     * Get financialEvaluation
     *
     * @return string
     */
    public function getFinancialEvaluation()
    {
        return $this->financialEvaluation;
    }

    /**
     * Set financialMinLevel
     *
     * @param string $financialMinLevel
     *
     * @return Contract
     */
    public function setFinancialMinLevel($financialMinLevel)
    {
        $this->financialMinLevel = $financialMinLevel;

        return $this;
    }

    /**
     * Get financialMinLevel
     *
     * @return string
     */
    public function getFinancialMinLevel()
    {
        return $this->financialMinLevel;
    }

    /**
     * Set technicalEvaluation
     *
     * @param string $technicalEvaluation
     *
     * @return Contract
     */
    public function setTechnicalEvaluation($technicalEvaluation)
    {
        $this->technicalEvaluation = $technicalEvaluation;

        return $this;
    }

    /**
     * Get technicalEvaluation
     *
     * @return string
     */
    public function getTechnicalEvaluation()
    {
        return $this->technicalEvaluation;
    }

    /**
     * Set technicalMinLevel
     *
     * @param string $technicalMinLevel
     *
     * @return Contract
     */
    public function setTechnicalMinLevel($technicalMinLevel)
    {
        $this->technicalMinLevel = $technicalMinLevel;

        return $this;
    }

    /**
     * Get technicalMinLevel
     *
     * @return string
     */
    public function getTechnicalMinLevel()
    {
        return $this->technicalMinLevel;
    }

    /**
     * Set awardCreteriaType
     *
     * @param string $awardCreteriaType
     *
     * @return Contract
     */
    public function setAwardCreteriaType($awardCreteriaType)
    {
        $this->awardCreteriaType = $awardCreteriaType;

        return $this;
    }

    /**
     * Get awardCreteriaType
     *
     * @return string
     */
    public function getAwardCreteriaType()
    {
        return $this->awardCreteriaType;
    }

    /**
     * Set isPayableDocument
     *
     * @param string $isPayableDocument
     *
     * @return Contract
     */
    public function setIsPayableDocument($isPayableDocument)
    {
        $this->isPayableDocument = $isPayableDocument;

        return $this;
    }

    /**
     * Get isPayableDocument
     *
     * @return string
     */
    public function getIsPayableDocument()
    {
        return $this->isPayableDocument;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Contract
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set paymentCurrency
     *
     * @param string $paymentCurrency
     *
     * @return Contract
     */
    public function setPaymentCurrency($paymentCurrency)
    {
        $this->paymentCurrency = $paymentCurrency;

        return $this;
    }

    /**
     * Get paymentCurrency
     *
     * @return string
     */
    public function getPaymentCurrency()
    {
        return $this->paymentCurrency;
    }

    /**
     * Set paymentTerms
     *
     * @param string $paymentTerms
     *
     * @return Contract
     */
    public function setPaymentTerms($paymentTerms)
    {
        $this->paymentTerms = $paymentTerms;

        return $this;
    }

    /**
     * Get paymentTerms
     *
     * @return string
     */
    public function getPaymentTerms()
    {
        return $this->paymentTerms;
    }

    /**
     * Set closingDate
     *
     * @param \DateTime $closingDate
     *
     * @return Contract
     */
    public function setClosingDate($closingDate)
    {
        $this->closingDate = $closingDate;

        return $this;
    }

    /**
     * Get closingDate
     *
     * @return \DateTime
     */
    public function getClosingDate()
    {
        return $this->closingDate;
    }

    /**
     * Set openingDate
     *
     * @param \DateTime $openingDate
     *
     * @return Contract
     */
    public function setOpeningDate($openingDate)
    {
        $this->openingDate = $openingDate;

        return $this;
    }

    /**
     * Get openingDate
     *
     * @return \DateTime
     */
    public function getOpeningDate()
    {
        return $this->openingDate;
    }

    /**
     * Set isPersonAuthorised
     *
     * @param string $isPersonAuthorised
     *
     * @return Contract
     */
    public function setIsPersonAuthorised($isPersonAuthorised)
    {
        $this->isPersonAuthorised = $isPersonAuthorised;

        return $this;
    }

    /**
     * Get isPersonAuthorised
     *
     * @return string
     */
    public function getIsPersonAuthorised()
    {
        return $this->isPersonAuthorised;
    }

    /**
     * Set authorisedPerson
     *
     * @param string $authorisedPerson
     *
     * @return Contract
     */
    public function setAuthorisedPerson($authorisedPerson)
    {
        $this->authorisedPerson = $authorisedPerson;

        return $this;
    }

    /**
     * Get authorisedPerson
     *
     * @return string
     */
    public function getAuthorisedPerson()
    {
        return $this->authorisedPerson;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Contract
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
     * @return Contract
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
     * Set status
     *
     * @param string $status
     *
     * @return Contract
     */
    public function setStatus($status)
    {
        $this->status = $status;
      return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set adminUser
     *
     * @param \AppBundle\Entity\AdminUser $adminUser
     *
     * @return Contract
     */
    public function setAdminUser(\AppBundle\Entity\AdminUser $adminUser = null)
    {
        $this->adminUser = $adminUser;

        return $this;
    }

    /**
     * Get adminUser
     *
     * @return \AppBundle\Entity\AdminUser
     */
    public function getAdminUser()
    {
        return $this->adminUser;
    }

    /**
     * Add associatedOfficer
     *
     * @param \AppBundle\Entity\ContractOfficer $associatedOfficer
     *
     * @return Contract
     */
    public function addAssociatedOfficer(\AppBundle\Entity\ContractOfficer $associatedOfficer)
    {
        $this->associatedOfficers[] = $associatedOfficer;

        return $this;
    }

    /**
     * Remove associatedOfficer
     *
     * @param \AppBundle\Entity\ContractOfficer $associatedOfficer
     */
    public function removeAssociatedOfficer(\AppBundle\Entity\ContractOfficer $associatedOfficer)
    {
        $this->associatedOfficers->removeElement($associatedOfficer);
    }

    /**
     * Get associatedOfficers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssociatedOfficers()
    {
        return $this->associatedOfficers;
    }

    /**
     * Add contractDocument
     *
     * @param \AppBundle\Entity\ContractDocument $contractDocument
     *
     * @return Contract
     */
    public function addContractDocument(\AppBundle\Entity\ContractDocument $contractDocument)
    {
        $this->contractDocuments[] = $contractDocument;

        return $this;
    }

    /**
     * Remove contractDocument
     *
     * @param \AppBundle\Entity\ContractDocument $contractDocument
     */
    public function removeContractDocument(\AppBundle\Entity\ContractDocument $contractDocument)
    {
        $this->contractDocuments->removeElement($contractDocument);
    }

    /**
     * Get contractDocuments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractDocuments()
    {
        return $this->contractDocuments;
    }

    /**
     * Add contractEvaluation
     *
     * @param \AppBundle\Entity\ContractEvaluation $contractEvaluation
     *
     * @return Contract
     */
    public function addContractEvaluation(\AppBundle\Entity\ContractEvaluation $contractEvaluation)
    {
         $contractEvaluation->setContract($this);
         $this->contractEvaluation->add($contractEvaluation);
    }

    /**
     * Remove contractEvaluation
     *
     * @param \AppBundle\Entity\ContractEvaluation $contractEvaluation
     */
    public function removeContractEvaluation(\AppBundle\Entity\ContractEvaluation $contractEvaluation)
    {
        $this->contractEvaluation->removeElement($contractEvaluation);
    }

    /**
     * Get contractEvaluation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractEvaluation()
    {
        return $this->contractEvaluation;
    }

    /**
     * Add preQualified
     *
     * @param \AppBundle\Entity\PreQualified $preQualified
     *
     * @return Contract
     */
    public function addPreQualified(\AppBundle\Entity\PreQualified $preQualified)
    {
        $this->preQualified[] = $preQualified;

        return $this;
    }

    /**
     * Remove preQualified
     *
     * @param \AppBundle\Entity\PreQualified $preQualified
     */
    public function removePreQualified(\AppBundle\Entity\PreQualified $preQualified)
    {
        $this->preQualified->removeElement($preQualified);
    }

    /**
     * Get preQualified
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreQualified()
    {
        return $this->preQualified;
    }

    /**
     * Add bid
     *
     * @param \AppBundle\Entity\Bid $bid
     *
     * @return Contract
     */
    public function addBid(\AppBundle\Entity\Bid $bid)
    {
        $this->bids[] = $bid;

        return $this;
    }

    /**
     * Remove bid
     *
     * @param \AppBundle\Entity\Bid $bid
     */
    public function removeBid(\AppBundle\Entity\Bid $bid)
    {
        $this->bids->removeElement($bid);
    }

    /**
     * Get bids
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBids()
    {
        return $this->bids;
    }

    /**
     * Set referenceNumber
     *
     * @param string $referenceNumber
     *
     * @return Contract
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * Get referenceNumber
     *
     * @return string
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * Set procedureType
     *
     * @param string $procedureType
     *
     * @return Contract
     */
    public function setProcedureType($procedureType)
    {
        $this->procedureType = $procedureType;

        return $this;
    }

    /**
     * Get procedureType
     *
     * @return string
     */
    public function getProcedureType()
    {
        return $this->procedureType;
    }

    /**
     * Set hasVariants
     *
     * @param string $hasVariants
     *
     * @return Contract
     */
    public function setHasVariants($hasVariants)
    {
        $this->hasVariants = $hasVariants;

        return $this;
    }

    /**
     * Get hasVariants
     *
     * @return string
     */
    public function getHasVariants()
    {
        return $this->hasVariants;
    }

    /**
     * Set contractDurationType
     *
     * @param string $contractDurationType
     *
     * @return Contract
     */
    public function setContractDurationType($contractDurationType)
    {
        $this->contractDurationType = $contractDurationType;

        return $this;
    }

    /**
     * Get contractDurationType
     *
     * @return string
     */
    public function getContractDurationType()
    {
        return $this->contractDurationType;
    }

    /**
     * Set exactDurationType
     *
     * @param string $exactDurationType
     *
     * @return Contract
     */
    public function setExactDurationType($exactDurationType)
    {
        $this->exactDurationType = $exactDurationType;

        return $this;
    }

    /**
     * Get exactDurationType
     *
     * @return string
     */
    public function getExactDurationType()
    {
        return $this->exactDurationType;
    }

    /**
     * Set exactDuration
     *
     * @param integer $exactDuration
     *
     * @return Contract
     */
    public function setExactDuration($exactDuration)
    {
        $this->exactDuration = $exactDuration;

        return $this;
    }

    /**
     * Get exactDuration
     *
     * @return integer
     */
    public function getExactDuration()
    {
        return $this->exactDuration;
    }

    /**
     * Set rangeStartDate
     *
     * @param \DateTime $rangeStartDate
     *
     * @return Contract
     */
    public function setRangeStartDate($rangeStartDate)
    {
        $this->rangeStartDate = $rangeStartDate;

        return $this;
    }

    /**
     * Get rangeStartDate
     *
     * @return \DateTime
     */
    public function getRangeStartDate()
    {
        return $this->rangeStartDate;
    }

    /**
     * Set rangeEndDate
     *
     * @param \DateTime $rangeEndDate
     *
     * @return Contract
     */
    public function setRangeEndDate($rangeEndDate)
    {
        $this->rangeEndDate = $rangeEndDate;

        return $this;
    }

    /**
     * Get rangeEndDate
     *
     * @return \DateTime
     */
    public function getRangeEndDate()
    {
        return $this->rangeEndDate;
    }

    /**
     * Set hasOtherCondition
     *
     * @param string $hasOtherCondition
     *
     * @return Contract
     */
    public function setHasOtherCondition($hasOtherCondition)
    {
        $this->hasOtherCondition = $hasOtherCondition;

        return $this;
    }

    /**
     * Get hasOtherCondition
     *
     * @return string
     */
    public function getHasOtherCondition()
    {
        return $this->hasOtherCondition;
    }

    /**
     * Set otherCondition
     *
     * @param string $otherCondition
     *
     * @return Contract
     */
    public function setOtherCondition($otherCondition)
    {
        $this->otherCondition = $otherCondition;

        return $this;
    }

    /**
     * Get otherCondition
     *
     * @return string
     */
    public function getOtherCondition()
    {
        return $this->otherCondition;
    }

    /**
     * Set openingVenue
     *
     * @param string $openingVenue
     *
     * @return Contract
     */
    public function setOpeningVenue($openingVenue)
    {
        $this->openingVenue = $openingVenue;

        return $this;
    }

    /**
     * Get openingVenue
     *
     * @return string
     */
    public function getOpeningVenue()
    {
        return $this->openingVenue;
    }

    /**
     * Set exactCurrency
     *
     * @param string $exactCurrency
     *
     * @return Contract
     */
    public function setExactCurrency($exactCurrency)
    {
        $this->exactCurrency = $exactCurrency;

        return $this;
    }

    /**
     * Get exactCurrency
     *
     * @return string
     */
    public function getExactCurrency()
    {
        return $this->exactCurrency;
    }

    /**
     * Set rangeCurrency
     *
     * @param string $rangeCurrency
     *
     * @return Contract
     */
    public function setRangeCurrency($rangeCurrency)
    {
        $this->rangeCurrency = $rangeCurrency;

        return $this;
    }

    /**
     * Get rangeCurrency
     *
     * @return string
     */
    public function getRangeCurrency()
    {
        return $this->rangeCurrency;
    }
    

    /**
     * Set payments
     *
     * @param \AppBundle\Entity\Payment $payments
     *
     * @return Contract
     */
    public function setPayments(\AppBundle\Entity\Payment $payments = null)
    {
        $this->payments = $payments;

        return $this;
    }

    /**
     * Get payments
     *
     * @return \AppBundle\Entity\Payment
     */
    public function getPayments()
    {
        return $this->payments;
    }
}

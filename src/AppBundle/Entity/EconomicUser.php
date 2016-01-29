<?php

namespace AppBundle\Entity;

/**
 * Description of EconomicUser
 *
 * @author lambert
 */
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="economicuser")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EconomicUserRepository")
 * @ORM\HasLifecycleCallBacks()
 */

class EconomicUser implements UserInterface,  \Serializable{
    
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
     * @ORM\Column(name="phone_number",type="string",length=15,unique=true)
     */
    protected $phoneNumber;
    
    /**
     * @ORM\Column(type="string",length=60)
     */
    protected $address;
    
    /**
     * @ORM\Column(name="company_name",type="string",length=60,unique=true)
     */
    protected $companyName;
    
    /**
     * @ORM\Column(name="tax_country",type="string",length=60)
     */
    protected $taxCountry;
    
    /**
     * @ORM\Column(name="company_registration_number",type="string",length=60,unique=true)
     */
    protected $companyRegistrationNumber;
    
    /**
     * @ORM\Column(type="string",length=60,unique=true)
     */
    protected $taxId;
    
    protected $temp;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;
    
    /**
     * @ORM\Column(name="logo_path",type="string", length=255, nullable=true)
     */
    public $logoPath;
    
    /**
     * @ORM\Column(name="is_active",type="boolean")
     */
    protected $isActive;
    
    /**
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;
    
    /**
     * @ORM\Column(name="updated_at",type="datetime")
     */
    protected $updatedAt;
    
    /**
     * @ORM\OneToMany(targetEntity="PreQualified",mappedBy="companyName")
     */
    protected $preQualified;

    /**
     * @ORM\OneToMany(targetEntity="Bid",mappedBy="economicUser")
     */
    protected $bids;
    
    /**
     * @ORM\OneToMany(targetEntity="Portfolio",mappedBy="economicUser",cascade={"persist"})
     */
    protected $portfolios;
    
    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRoles() {
        return array('ROLE_USER');
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
        list($this->id,$this->username,$this->password)=  unserialize($serialized);
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
     * Set username
     *
     * @param string $username
     *
     * @return EconomicUser
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
     * @return EconomicUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return EconomicUser
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return EconomicUser
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return EconomicUser
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return EconomicUser
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set taxCountry
     *
     * @param string $taxCountry
     *
     * @return EconomicUser
     */
    public function setTaxCountry($taxCountry)
    {
        $this->taxCountry = $taxCountry;

        return $this;
    }

    /**
     * Get taxCountry
     *
     * @return string
     */
    public function getTaxCountry()
    {
        return $this->taxCountry;
    }

    /**
     * Set companyRegistrationNumber
     *
     * @param string $companyRegistrationNumber
     *
     * @return EconomicUser
     */
    public function setCompanyRegistrationNumber($companyRegistrationNumber)
    {
        $this->companyRegistrationNumber = $companyRegistrationNumber;

        return $this;
    }

    /**
     * Get companyRegistrationNumber
     *
     * @return string
     */
    public function getCompanyRegistrationNumber()
    {
        return $this->companyRegistrationNumber;
    }

    /**
     * Set taxId
     *
     * @param string $taxId
     *
     * @return EconomicUser
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;

        return $this;
    }

    /**
     * Get taxId
     *
     * @return string
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return EconomicUser
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return EconomicUser
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
     * @return EconomicUser
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EconomicUser
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        $this->createdAt=new \DateTime();
        
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue() {
        $this->updatedAt=new \DateTime();
        
    }
       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->preQualified = new ArrayCollection();
        $this->bids = new ArrayCollection();
        $this->portfolios = new ArrayCollection();
    }

    /**
     * Add preQualified
     *
     * @param \AppBundle\Entity\PreQualified $preQualified
     *
     * @return EconomicUser
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
     * @return EconomicUser
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
    
    public function __toString() {
        return $this->firstname." ".$this->lastname. "-".$this->companyName;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->logoPath
            ? null
            : $this->getUploadRootDir().'/'.$this->logoPath;
    }
    
    public function getWebPath()
    {
        return null === $this->logoPath
            ? null
            : $this->getUploadDir().'/'.$this->logoPath;
    }
    
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return '/var/www/e_tendering'.'/web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/logos';
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
      if (isset($this->logoPath)) {
        // store the old name to delete after the update
        $this->temp = $this->logoPath;
        $this->logoPath = null;
      } else {
        $this->logoPath = 'initial';
      }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->logoPath = $filename.'.'.$this->getFile()->guessExtension();
        }
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
            // if there is an error when moving the file, an exception will
            // be automatically thrown by move(). This will properly prevent
            // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->logoPath);
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
     $this->file = null;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }
    
    /**
     * Add portfolio
     *
     * @param \AppBundle\Entity\Portfolio $portfolio
     *
     * @return EconomicUser
     */
    public function addPortfolio(\AppBundle\Entity\Portfolio $portfolio)
    {
        $portfolio->setEconomicUser($this);
        $this->portfolios->add($portfolio);
    }

    /**
     * Remove portfolio
     *
     * @param \AppBundle\Entity\Portfolio $portfolio
     */
    public function removePortfolio(\AppBundle\Entity\Portfolio $portfolio)
    {
        $this->portfolios->removeElement($portfolio);
    }

    /**
     * Get portfolios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPortfolios()
    {
        return $this->portfolios;
    }

    /**
     * Set logoPath
     *
     * @param string $logoPath
     *
     * @return EconomicUser
     */
    public function setLogoPath($logoPath)
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    /**
     * Get logoPath
     *
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return EconomicUser
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
}

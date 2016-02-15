<?php

namespace AppBundle\Entity;

/**
 * Description of Tender
 *
 * @author lambert
 */
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tender")
 * @ORM\HasLifecycleCallBacks()
 */
class Tender {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bid", inversedBy="tenders")
     * @ORM\JoinColumn(name="bid",referencedColumnName="id")
     */
    protected $bid;
    
    /**
     * @ORM\Column(type="string",length=255)
     */
    protected $title;

    /**
     * @ORM\Column(name="file_name",type="string",length=255)
     */
    protected $fileName;
    
    /**
     * @ORM\Column(name="file_path",type="string",length=255)
     */
    protected $filePath;
    
    protected $temp;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;
    
    /**
     * @ORM\Column(name="signature_date",type="datetime")
     */
    protected $signatureDate;
    
    /**
     * @ORM\Column(name="uploaded_at",type="datetime")
     */
    protected $uploadedAt;
    
    /**
     * @ORM\Column(name="updated_at",type="datetime")
     */
    protected $updatedAt;
    
    /**
     * @ORM\Column(type="string",length=25)
     */
    protected $status;
    
    /**
     * @ORM\PrePersist
     */
    public function setUploadedAtValue(){
        $this->uploadedAt = new \DateTime();
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setUpdatedAtPersistValue(){
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue(){
        $this->updatedAt = new \DateTime();
    }
    /**
     * @ORM\PrePersist
     */
    public function setSignatureDateValue(){
        $this->signatureDate = new \DateTime();
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setStatusValue(){
        $this->status = "Complete";
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
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Tender
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    
    /**
     * Set uploadedAt
     *
     * @param \DateTime $uploadedAt
     *
     * @return Tender
     */
    public function setUploadedAt($uploadedAt)
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * Get uploadedAt
     *
     * @return \DateTime
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Tender
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
     * Set bid
     *
     * @param \AppBundle\Entity\Bid $bid
     *
     * @return Tender
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

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Tender
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
     * Set filePath
     *
     * @param string $filePath
     *
     * @return Tender
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadRootDir().'/'.$this->filePath;
    }
    
    public function getWebPath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadDir().'/'.$this->filePath;
    }
    
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
      return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/tenders';
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
      if (isset($this->filePath)) {
        // store the old name to delete after the update
        $this->temp = $this->filePath;
        $this->filePath = null;
      } else {
        $this->filePath = 'initial';
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
            $this->filePath = $filename.'.'.$this->getFile()->guessExtension();
            $this->fileName = $this->getFile()->getClientOriginalName();
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
        $this->getFile()->move($this->getUploadRootDir(), $this->filePath);
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Tender
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
     * Set signatureDate
     *
     * @param \DateTime $signatureDate
     *
     * @return Tender
     */
    public function setSignatureDate($signatureDate)
    {
        $this->signatureDate = $signatureDate;

        return $this;
    }

    /**
     * Get signatureDate
     *
     * @return \DateTime
     */
    public function getSignatureDate()
    {
        return $this->signatureDate;
    }
}

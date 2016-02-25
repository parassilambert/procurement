<?php

namespace AppBundle\Entity;

/**
 * Description of Audit
 *
 * @author lambert
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="audit")
 * @ORM\HasLifecycleCallBacks()
 */
class Audit {
    
/**
 * @ORM\Column(type="integer")
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="AUTO")
 */
protected $id;

/**
 * @ORM\Column(name="username",type="string",length=60)
 */
protected $username;

/**
 * @ORM\Column(name="name",type="string",length=60)
 */
protected $name;

/**
 * @ORM\Column(name="function_type",type="string",length=60)
 */
protected $functionType;

/**
 * @ORM\Column(name="event_type",type="string",length=60)
 */
protected $eventType;

/**
 * @ORM\ManyToOne(targetEntity="Contract",inversedBy="audits")
 * @ORM\JoinColumn(name="dossier", referencedColumnName="id")
 */
protected $dossier;

/**
 * @ORM\Column(name="created_at",type="datetime")
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
     * Set username
     *
     * @param string $username
     *
     * @return Audit
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set functionType
     *
     * @param string $functionType
     *
     * @return Audit
     */
    public function setFunctionType($functionType)
    {
        $this->functionType = $functionType;

        return $this;
    }

    /**
     * Get functionType
     *
     * @return string
     */
    public function getFunctionType()
    {
        return $this->functionType;
    }

    /**
     * Set eventType
     *
     * @param string $eventType
     *
     * @return Audit
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Audit
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
     * Set dossier
     *
     * @param \AppBundle\Entity\Contract $dossier
     *
     * @return Audit
     */
    public function setDossier(\AppBundle\Entity\Contract $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \AppBundle\Entity\Contract
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Audit
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

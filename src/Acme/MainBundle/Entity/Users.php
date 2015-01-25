<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Users
 *
 * @ORM\Table(name="users", indexes={@ORM\Index(name="SECONDARY", columns={"email"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Users
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=70, nullable=true)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    private $isDeleted = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_copy", type="boolean", nullable=false)
     */
    private $isCopy = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;



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
     * Set email
     *
     * @param string $email
     * @return Users
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Users
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isCopy
     *
     * @param boolean $isCopy
     * @return Users
     */
    public function setIsCopy($isCopy)
    {
        $this->isCopy = $isCopy;

        return $this;
    }

    /**
     * Get isCopy
     *
     * @return boolean 
     */
    public function getIsCopy()
    {
        return $this->isCopy;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Users
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
     * @return Users
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
     *  @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        $this->setUpdatedAt(new \DateTime());
        if (is_null($this->getCreatedAt())) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

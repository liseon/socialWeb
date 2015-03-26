<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnnouncesRelation
 */
class AnnouncesRelation
{
    /**
     * @var integer
     */
    private $id;


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
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $friendId;

    /**
     * @var integer
     */
    private $copyOwnerId;

    /**
     * @var integer
     */
    private $copyPostId;

    /**
     * @var \DateTime
     */
    private $copyPostCreatedAt;

    /**
     * @var \Acme\MainBundle\Entity\Announces
     */
    private $announce;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return AnnouncesRelation
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set friendId
     *
     * @param integer $friendId
     * @return AnnouncesRelation
     */
    public function setFriendId($friendId)
    {
        $this->friendId = $friendId;

        return $this;
    }

    /**
     * Get friendId
     *
     * @return integer 
     */
    public function getFriendId()
    {
        return $this->friendId;
    }

    /**
     * Set copyOwnerId
     *
     * @param integer $copyOwnerId
     * @return AnnouncesRelation
     */
    public function setCopyOwnerId($copyOwnerId)
    {
        $this->copyOwnerId = $copyOwnerId;

        return $this;
    }

    /**
     * Get copyOwnerId
     *
     * @return integer 
     */
    public function getCopyOwnerId()
    {
        return $this->copyOwnerId;
    }

    /**
     * Set copyPostId
     *
     * @param integer $copyPostId
     * @return AnnouncesRelation
     */
    public function setCopyPostId($copyPostId)
    {
        $this->copyPostId = $copyPostId;

        return $this;
    }

    /**
     * Get copyPostId
     *
     * @return integer 
     */
    public function getCopyPostId()
    {
        return $this->copyPostId;
    }

    /**
     * Set copyPostCreatedAt
     *
     * @param \DateTime $copyPostCreatedAt
     * @return AnnouncesRelation
     */
    public function setCopyPostCreatedAt($copyPostCreatedAt)
    {
        $this->copyPostCreatedAt = $copyPostCreatedAt;

        return $this;
    }

    /**
     * Get copyPostCreatedAt
     *
     * @return \DateTime 
     */
    public function getCopyPostCreatedAt()
    {
        return $this->copyPostCreatedAt;
    }

    /**
     * Set announce
     *
     * @param \Acme\MainBundle\Entity\Announces $announce
     * @return AnnouncesRelation
     */
    public function setAnnounce(\Acme\MainBundle\Entity\Announces $announce)
    {
        $this->announce = $announce;

        return $this;
    }

    /**
     * Get announce
     *
     * @return \Acme\MainBundle\Entity\Announces 
     */
    public function getAnnounce()
    {
        return $this->announce;
    }
    /**
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        // Add your code here
    }
}

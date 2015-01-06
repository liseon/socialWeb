<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Announces
 *
 * @ORM\Table(name="Announces", indexes={@ORM\Index(name="SECONDARY", columns={"for_user_id"}), @ORM\Index(name="moderator", columns={"moderator_id"})})
 * @ORM\Entity
 */
class Announces
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
     * @ORM\Column(name="source_type", type="string", nullable=false)
     */
    private $sourceType;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_id", type="integer", nullable=false)
     */
    private $ownerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="friend_id", type="integer", nullable=false)
     */
    private $friendId;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var boolean
     *
     * @ORM\Column(name="moderation", type="boolean", nullable=false)
     */
    private $moderation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="moderation_updated", type="datetime", nullable=true)
     */
    private $moderationUpdated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \Acme\MainBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Acme\MainBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="for_user_id", referencedColumnName="id")
     * })
     */
    private $forUser;

    /**
     * @var \Acme\MainBundle\Entity\Moderators
     *
     * @ORM\ManyToOne(targetEntity="Acme\MainBundle\Entity\Moderators")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="moderator_id", referencedColumnName="id")
     * })
     */
    private $moderator;



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
     * Set sourceType
     *
     * @param string $sourceType
     * @return Announces
     */
    public function setSourceType($sourceType)
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    /**
     * Get sourceType
     *
     * @return string 
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * Set ownerId
     *
     * @param integer $ownerId
     * @return Announces
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * Get ownerId
     *
     * @return integer 
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set friendId
     *
     * @param integer $friendId
     * @return Announces
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
     * Set text
     *
     * @param string $text
     * @return Announces
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Announces
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set moderation
     *
     * @param boolean $moderation
     * @return Announces
     */
    public function setModeration($moderation)
    {
        $this->moderation = $moderation;

        return $this;
    }

    /**
     * Get moderation
     *
     * @return boolean 
     */
    public function getModeration()
    {
        return $this->moderation;
    }

    /**
     * Set moderationUpdated
     *
     * @param \DateTime $moderationUpdated
     * @return Announces
     */
    public function setModerationUpdated($moderationUpdated)
    {
        $this->moderationUpdated = $moderationUpdated;

        return $this;
    }

    /**
     * Get moderationUpdated
     *
     * @return \DateTime 
     */
    public function getModerationUpdated()
    {
        return $this->moderationUpdated;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Announces
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
     * @return Announces
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
     * Set forUser
     *
     * @param \Acme\MainBundle\Entity\Users $forUser
     * @return Announces
     */
    public function setForUser(\Acme\MainBundle\Entity\Users $forUser = null)
    {
        $this->forUser = $forUser;

        return $this;
    }

    /**
     * Get forUser
     *
     * @return \Acme\MainBundle\Entity\Users 
     */
    public function getForUser()
    {
        return $this->forUser;
    }

    /**
     * Set moderator
     *
     * @param \Acme\MainBundle\Entity\Moderators $moderator
     * @return Announces
     */
    public function setModerator(\Acme\MainBundle\Entity\Moderators $moderator = null)
    {
        $this->moderator = $moderator;

        return $this;
    }

    /**
     * Get moderator
     *
     * @return \Acme\MainBundle\Entity\Moderators 
     */
    public function getModerator()
    {
        return $this->moderator;
    }
}

<?php

namespace Acme\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use \Acme\VkBundle\Entity\VkAttachments;

/**
 * Announces
 *
 * @ORM\Table(name="Announces", indexes={@ORM\Index(name="SECONDARY", columns={"for_user_id"}), @ORM\Index(name="moderator", columns={"moderator_id"}), @ORM\Index(name="owner", columns={"owner_id"})})
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
     * @var string
     *
     * @ORM\Column(name="owner_photo", type="string", nullable=false)
     */
    private $ownerPhoto = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="post_id", type="integer", nullable=false)
     */
    private $postId;

    /**
     * @var integer
     *
     * @ORM\Column(name="copy_owner_id", type="integer", nullable=false)
     */
    private $copyOwnerId = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="copy_owner_photo", type="string", nullable=false)
     */
    private $copyOwnerPhoto = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="copy_post_id", type="integer", nullable=false)
     */
    private $copyPostId = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="friend_id", type="integer", nullable=false)
     */
    private $friendId = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
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
    private $moderation = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="moderation_updated", type="datetime", nullable=true)
     */
    private $moderationUpdated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="post_created_at", type="datetime", nullable=false)
     */
    private $postCreatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="copy_post_created_at", type="datetime", nullable=false)
     */
    private $copyPostCreatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var int
     *
     */
    private $forUserId;

    /**
     * @var int
     *
     */
    private $moderator = 0;

    /**
     * @ORM\OneToMany(targetEntity="VkAttachments", mappedBy="announces")
     */
    protected $attachments;

    public function __construct() {
        $this->attachments = new ArrayCollection();
    }


        /**
     * @return array
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * @param mixed $attachments
     * @return Announces
     */
    public function setAttachments($attachments) {
        $this->attachments = $attachments;

        return $this;
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
     * @return string
     */
    public function getOwnerPhoto()
    {
        return $this->ownerPhoto;
    }

    /**
     * @param string $ownerPhoto
     * @return Announces
     */
    public function setOwnerPhoto($ownerPhoto)
    {
        $this->ownerUrl = $ownerPhoto;

        return $this;
    }

    /**
     * @return int
     */
    public function getCopyOwnerId()
    {
        return $this->copyOwnerId;
    }

    /**
     * @param int $copyOwnerId
     * @return Announces
     */
    public function setCopyOwnerId($copyOwnerId)
    {
        $this->copyOwnerId = (int)$copyOwnerId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopyOwnerPhoto()
    {
        return $this->copyOwnerPhoto;
    }

    /**
     * @param string $copyOwnerPhoto
     * @return Announces
     */
    public function setCopyOwnerPhoto($copyOwnerPhoto)
    {
        $this->copyOwnerPhoto = $copyOwnerPhoto;

        return $this;
    }


    /**
     * @return int
     */
    public function getCopyPostId()
    {
        return $this->copyPostId;
    }

    /**
     * @param int $copyPostId
     * @return Announces
     */
    public function setCopyPostId($copyPostId)
    {
        $this->copyPostId = (int)$copyPostId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCopyPostCreatedAt()
    {
        return $this->copyPostCreatedAt;
    }

    /**
     * @param \DateTime $copyPostCreatedAt
     * @return Announces
     */
    public function setCopyPostCreatedAt($copyPostCreatedAt)
    {
        $this->copyPostCreatedAt = $copyPostCreatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPostCreatedAt()
    {
        return $this->postCreatedAt;
    }

    /**
     * @param \DateTime $postCreatedAt
     * @return Announces
     */
    public function setPostCreatedAt($postCreatedAt)
    {
        $this->postCreatedAt = $postCreatedAt;

        return $this;
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
        $this->ownerId = (int)$ownerId;

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
     * Set postId
     *
     * @param integer $postId
     * @return Announces
     */
    public function setPostId($postId)
    {
        $this->postId = (int)$postId;

        return $this;
    }

    /**
     * Get postId
     *
     * @return integer 
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set friendId
     *
     * @param integer $friendId
     * @return Announces
     */
    public function setFriendId($friendId)
    {
        $this->friendId = (int)$friendId;

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
        $this->points = (int)$points;

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
        $this->setModerationUpdated(new \DateTime());

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
    public function setModerationUpdated(\DateTime $moderationUpdated)
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
     * Set forUserId
     *
     * @param int $forUserId
     * @return Announces
     */
    public function setForUserId($forUserId = 0)
    {
        $this->forUserId = (int)$forUserId;

        return $this;
    }

    /**
     * Get forUserId
     *
     * @return int
     */
    public function getForUserId()
    {
        return $this->forUserId;
    }

    /**
     * Set moderator
     *
     * @param int $moderator
     * @return Announces
     */
    public function setModerator($moderator)
    {
        $this->moderator = (int)$moderator;

        return $this;
    }

    /**
     * Get moderator
     *
     * @return int
     */
    public function getModerator()
    {
        return $this->moderator;
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

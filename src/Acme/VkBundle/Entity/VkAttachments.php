<?php

namespace Acme\VkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VkAttachments
 *
 * @ORM\Table(name="vk_attachments", indexes={@ORM\Index(name="SECONDARY", columns={"announce_id"}), @ORM\Index(name="moderator", columns={"moderator_id"}), @ORM\Index(name="fk_attachments_3_idx", columns={"vk_id"})})
 * @ORM\Entity
 */
class VkAttachments
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
     * @var integer
     *
     * @ORM\Column(name="vk_owner", type="integer", nullable=false)
     */
    private $vkOwner;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var boolean
     *
     * @ORM\Column(name="moderation", type="boolean", nullable=false)
     */
    private $moderation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="moderation_updated", type="datetime", nullable=false)
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
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \Acme\MainBundle\Entity\Announces
     *
     * @ORM\ManyToOne(targetEntity="\Acme\MainBundle\Entity\Announces")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="announce_id", referencedColumnName="id")
     * })
     */
    private $announce;

    /**
     * @var \Acme\MainBundle\Entity\Moderators
     *
     * @ORM\ManyToOne(targetEntity="\Acme\MainBundle\Entity\Moderators")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="moderator_id", referencedColumnName="id")
     * })
     */
    private $moderator;

    /**
     * @var \Acme\VkBundle\Entity\VkUsers
     *
     * @ORM\ManyToOne(targetEntity="Acme\VkBundle\Entity\VkUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vk_id", referencedColumnName="vk_id")
     * })
     */
    private $vk;



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
     * Set vkOwner
     *
     * @param integer $vkOwner
     * @return VkAttachments
     */
    public function setVkOwner($vkOwner)
    {
        $this->vkOwner = $vkOwner;

        return $this;
    }

    /**
     * Get vkOwner
     *
     * @return integer 
     */
    public function getVkOwner()
    {
        return $this->vkOwner;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return VkAttachments
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return VkAttachments
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set moderation
     *
     * @param boolean $moderation
     * @return VkAttachments
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
     * @return VkAttachments
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
     * @return VkAttachments
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
     * @return VkAttachments
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
     * Set announce
     *
     * @param \Acme\MainBundle\Entity\Announces $announce
     * @return VkAttachments
     */
    public function setAnnounce(\Acme\MainBundle\Entity\Announces $announce = null)
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
     * Set moderator
     *
     * @param \Acme\MainBundle\Entity\Moderators $moderator
     * @return VkAttachments
     */
    public function setModerator(\Acme\MainBundle\Entity\Moderators $moderator = null)
    {
        $this->moderator = $moderator;

        return $this;
    }

    /**
     * Get moderator
     *
     * @return\Acme\MainBundle\Entity\Moderators
     */
    public function getModerator()
    {
        return $this->moderator;
    }

    /**
     * Set vk
     *
     * @param \Acme\VkBundle\Entity\VkUsers $vk
     * @return VkAttachments
     */
    public function setVk(\Acme\VkBundle\Entity\VkUsers $vk = null)
    {
        $this->vk = $vk;

        return $this;
    }

    /**
     * Get vk
     *
     * @return \Acme\VkBundle\Entity\VkUsers 
     */
    public function getVk()
    {
        return $this->vk;
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

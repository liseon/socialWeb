<?php

namespace Acme\VkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VkFriends
 *
 * @ORM\Table(name="vk_friends", indexes={@ORM\Index(name="friend", columns={"friend_id"})})
 * @ORM\Entity
 */
class VkFriends
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
     * @var \Acme\VkBundle\Entity\VkUsers
     *
     * @ORM\OneToOne(targetEntity="Acme\VkBundle\Entity\VkUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="vk_id", unique=true)
     * })
     */
    private $vkUser;

    /**
     * @var \Acme\VkBundle\Entity\VkUsers
     *
     * @ORM\ManyToOne(targetEntity="Acme\VkBundle\Entity\VkUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="friend_id", referencedColumnName="vk_id")
     * })
     */
    private $vkFriend;



    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return VkFriends
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
     * @return VkFriends
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
     * Set id
     *
     * @param \Acme\VkBundle\Entity\VkUsers $id
     * @return VkFriends
     */
    public function setId(\Acme\VkBundle\Entity\VkUsers $id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \Acme\VkBundle\Entity\VkUsers 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set friend
     *
     * @param \Acme\VkBundle\Entity\VkUsers $friend
     * @return VkFriends
     */
    public function setFriend(\Acme\VkBundle\Entity\VkUsers $friend = null)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return \Acme\VkBundle\Entity\VkUsers 
     */
    public function getFriend()
    {
        return $this->friend;
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

    /**
     * Set vkUser
     *
     * @param integer $vkUser
     * @return VkFriends
     */
    public function setVkUser($vkUser)
    {
        $this->vkUser = $vkUser;

        return $this;
    }

    /**
     * Get vkUser
     *
     * @return integer 
     */
    public function getVkUser()
    {
        return $this->vkUser;
    }

    /**
     * Set vkFriend
     *
     * @param integer $vkFriend
     * @return VkFriends
     */
    public function setVkFriend($vkFriend)
    {
        $this->vkFriend = $vkFriend;

        return $this;
    }

    /**
     * Get vkFriend
     *
     * @return integer 
     */
    public function getVkFriend()
    {
        return $this->vkFriend;
    }
}

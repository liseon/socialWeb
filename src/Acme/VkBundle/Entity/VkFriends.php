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
    private $id;

    /**
     * @var \Acme\VkBundle\Entity\VkUsers
     *
     * @ORM\ManyToOne(targetEntity="Acme\VkBundle\Entity\VkUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="friend_id", referencedColumnName="vk_id")
     * })
     */
    private $friend;



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
}

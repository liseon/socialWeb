<?php

namespace Acme\VkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VkParsingTasks
 *
 * @ORM\Table(name="vk_parsing_tasks", indexes={@ORM\Index(name="SECONDARY", columns={"vk_user_id"}), @ORM\Index(name="isdone", columns={"is_done"})})
 * @ORM\Entity
 */
class VkParsingTasks
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
     * @var boolean
     *
     * @ORM\Column(name="level", type="boolean", nullable=false)
     */
    private $level;

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
     * @var boolean
     *
     * @ORM\Column(name="is_done", type="boolean", nullable=false)
     */
    private $isDone = false;

    /**
     * @var int
     *
     */
    private $vkUser;



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
     * Set level
     *
     * @param boolean $level
     * @return VkParsingTasks
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return boolean 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return VkParsingTasks
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
     * @return VkParsingTasks
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
     * Set isDone
     *
     * @param boolean $isDone
     * @return VkParsingTasks
     */
    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;

        return $this;
    }

    /**
     * Get isDone
     *
     * @return boolean 
     */
    public function getIsDone()
    {
        return $this->isDone;
    }

    /**
     * Set vkUser
     *
     * @param int $vkUserId
     * @return VkParsingTasks
     */
    public function setVkUser($vkUserId = 0)
    {
        $this->vkUser = (int)$vkUserId;

        return $this;
    }

    /**
     * Get vkUser
     *
     * @return int
     */
    public function getVkUser()
    {
        return $this->vkUser;
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

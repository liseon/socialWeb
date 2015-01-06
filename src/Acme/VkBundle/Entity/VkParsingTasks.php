<?php

namespace Acme\VkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VkParsingTasks
 *
 * @ORM\Table(name="vk_parsing_tasks", indexes={@ORM\Index(name="SECONDARY", columns={"vk_user_id"})})
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
     * @ORM\Column(name="level", type="boolean", nullable=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="string", length=45, nullable=true)
     */
    private $createdAt;

    /**
     * @var \Acme\VkBundle\Entity\VkUsers
     *
     * @ORM\ManyToOne(targetEntity="Acme\VkBundle\Entity\VkUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vk_user_id", referencedColumnName="vk_id")
     * })
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
     * @param string $createdAt
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
     * @return string 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set vkUser
     *
     * @param \Acme\VkBundle\Entity\VkUsers $vkUser
     * @return VkParsingTasks
     */
    public function setVkUser(\Acme\VkBundle\Entity\VkUsers $vkUser = null)
    {
        $this->vkUser = $vkUser;

        return $this;
    }

    /**
     * Get vkUser
     *
     * @return \Acme\VkBundle\Entity\VkUsers 
     */
    public function getVkUser()
    {
        return $this->vkUser;
    }
}

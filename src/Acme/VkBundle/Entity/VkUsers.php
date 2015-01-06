<?php

namespace Acme\VkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VkUsers
 *
 * @ORM\Table(name="vk_users", uniqueConstraints={@ORM\UniqueConstraint(name="SECONDARY", columns={"user_id"})}, indexes={@ORM\Index(name="email", columns={"email"})})
 * @ORM\Entity
 */
class VkUsers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="vk_id", type="integer")
     * @ORM\Id
     */
    private $vkId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=70, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=true)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="token_expires_at", type="datetime", nullable=false)
     */
    private $tokenExpiresAt;

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
     * @var \Acme\MainBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Acme\MainBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Set vkId
     *
     * @param integer $vkId
     * @return VkUsers
     */
    public function setVkId($vkId)
    {
        $this->vkId = $vkId;

        return $this;
    }

    /**
     * Get vkId
     *
     * @return integer 
     */
    public function getVkId()
    {
        return $this->vkId;
    }


    /**
     * Set email
     *
     * @param string $email
     * @return VkUsers
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
     * Set token
     *
     * @param string $token
     * @return VkUsers
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set tokenExpiresAt
     *
     * @param \DateTime $tokenExpiresAt
     * @return VkUsers
     */
    public function setTokenExpiresAt($tokenExpiresAt)
    {
        $this->tokenExpiresAt = $tokenExpiresAt;

        return $this;
    }

    /**
     * Get tokenExpiresAt
     *
     * @return \DateTime 
     */
    public function getTokenExpiresAt()
    {
        return $this->tokenExpiresAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return VkUsers
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
     * @return VkUsers
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
     * Set user
     *
     * @param \Acme\MainBundle\Entity\Users $user
     * @return VkUsers
     */
    public function setUser(\Acme\MainBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Acme\MainBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
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

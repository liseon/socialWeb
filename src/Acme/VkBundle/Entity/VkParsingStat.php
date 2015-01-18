<?php

namespace Acme\VkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VkParsingStat
 *
 * @ORM\Table(name="vk_parsing_stat", indexes={@ORM\Index(name="type", columns={"agr_type"})})
 * @ORM\Entity
 */
class VkParsingStat
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
     * @ORM\Column(name="agr_type", type="string", nullable=false)
     */
    private $agrType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="level", type="boolean", nullable=false)
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var integer
     *
     * @ORM\Column(name="waiting", type="integer", nullable=false)
     */
    private $waiting;



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
     * Set agrType
     *
     * @param string $agrType
     * @return VkParsingStat
     */
    public function setAgrType($agrType)
    {
        $this->agrType = $agrType;

        return $this;
    }

    /**
     * Get agrType
     *
     * @return string 
     */
    public function getAgrType()
    {
        return $this->agrType;
    }

    /**
     * Set level
     *
     * @param boolean $level
     * @return VkParsingStat
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
     * Set count
     *
     * @param integer $count
     * @return VkParsingStat
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set waiting
     *
     * @param integer $waiting
     * @return VkParsingStat
     */
    public function setWaiting($waiting)
    {
        $this->waiting = $waiting;

        return $this;
    }

    /**
     * Get waiting
     *
     * @return integer 
     */
    public function getWaiting()
    {
        return $this->waiting;
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

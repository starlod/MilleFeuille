<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppEntity
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 *
 */
abstract class AppEntity
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt = null;

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Estimate
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
     * @return Estimate
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
     * INSERT 前処理
     * @ORM\PrePersist
     */
    public function setPrePersist()
    {
        if (empty($this->createdAt) || empty($this->updatedAt)) {
            $this->createdAt = new \Datetime();
            $this->updatedAt = $this->createdAt;
        }
    }

    /**
     * UPDATE 前処理
     * @ORM\PreUpdate
     */
    public function setPreUpdate()
    {
        $this->updatedAt = new \Datetime();
    }
}

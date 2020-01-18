<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friendship
 *
 * @ORM\Table(name="friendship")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FriendshipRepository")
 */
class Friendship
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="friends")
     *
     */
    private $user;


    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="friendsWithMe")
     *
     */
    private $friend;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_accepted", type="boolean", nullable=true)
     */
    private $isAccepted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accepted_on", type="datetime", nullable=true)
     */
    private $acceptedOn;
    

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Friendship
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
     * Set isAccepted
     *
     * @param boolean $isAccepted
     *
     * @return Friendship
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted
     *
     * @return bool
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * Set acceptedOn
     *
     * @param \DateTime $acceptedOn
     *
     * @return Friendship
     */
    public function setAcceptedOn($acceptedOn)
    {
        $this->acceptedOn = $acceptedOn;

        return $this;
    }

    /**
     * Get acceptedOn
     *
     * @return \DateTime
     */
    public function getAcceptedOn()
    {
        return $this->acceptedOn;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Friendship
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set friend
     *
     * @param \AppBundle\Entity\User $friend
     *
     * @return Friendship
     */
    public function setFriend(\AppBundle\Entity\User $friend)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return \AppBundle\Entity\User
     */
    public function getFriend()
    {
        return $this->friend;
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
}

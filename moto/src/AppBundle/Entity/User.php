<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;


    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="date", length=255)
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="job", type="string", length=255)
     */
    private $job;


    /**
     * The people who I think are my friends.
     *
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="user")
     */
    private $friends;

    /**
     * The people who think that I’m their friend.
     *
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="friend")
     */
    private $friendsWithMe;


    /**
     * @var \MailBundle\Entity\Mail
     *
     * @ORM\OneToMany(targetEntity="\MailBundle\Entity\Mail", mappedBy="createdBy")
     */
    private $sentMails;


    /**
     * @var \MailBundle\Entity\Mail
     *
     * @ORM\OneToMany(targetEntity="\MailBundle\Entity\Mail", mappedBy="sendTo")
     */
    private $receivedMails;


    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="created_by")
     */
    private $posts;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->sentMails = new ArrayCollection();
        $this->receivedMails = new ArrayCollection();
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     *
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set job
     *
     * @param string $job
     *
     * @return User
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Add post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return User
     */
    public function addPost(\AppBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \AppBundle\Entity\Post $post
     */
    public function removePost(\AppBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add sentMail
     *
     * @param \MailBundle\Entity\Mail $sentMail
     *
     * @return User
     */
    public function addSentMail(\MailBundle\Entity\Mail $sentMail)
    {
        $this->sentMails[] = $sentMail;

        return $this;
    }

    /**
     * Remove sentMail
     *
     * @param \MailBundle\Entity\Mail $sentMail
     */
    public function removeSentMail(\MailBundle\Entity\Mail $sentMail)
    {
        $this->sentMails->removeElement($sentMail);
    }

    /**
     * Get sentMails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSentMails()
    {
        return $this->sentMails;
    }

    /**
     * Add receivedMail
     *
     * @param \MailBundle\Entity\Mail $receivedMail
     *
     * @return User
     */
    public function addReceivedMail(\MailBundle\Entity\Mail $receivedMail)
    {
        $this->receivedMails[] = $receivedMail;

        return $this;
    }

    /**
     * Remove receivedMail
     *
     * @param \MailBundle\Entity\Mail $receivedMail
     */
    public function removeReceivedMail(\MailBundle\Entity\Mail $receivedMail)
    {
        $this->receivedMails->removeElement($receivedMail);
    }

    /**
     * Get receivedMails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReceivedMails()
    {
        return $this->receivedMails;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Add friend
     *
     * @param \AppBundle\Entity\Friendship $friend
     *
     * @return User
     */
    public function addFriend(\AppBundle\Entity\Friendship $friend)
    {
        $this->friends[] = $friend;

        return $this;
    }

    /**
     * Remove friend
     *
     * @param \AppBundle\Entity\Friendship $friend
     */
    public function removeFriend(\AppBundle\Entity\Friendship $friend)
    {
        $this->friends->removeElement($friend);
    }

    /**
     * Get friends
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Add friendsWithMe
     *
     * @param \AppBundle\Entity\Friendship $friendsWithMe
     *
     * @return User
     */
    public function addFriendsWithMe(\AppBundle\Entity\Friendship $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \AppBundle\Entity\Friendship $friendsWithMe
     */
    public function removeFriendsWithMe(\AppBundle\Entity\Friendship $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }
}

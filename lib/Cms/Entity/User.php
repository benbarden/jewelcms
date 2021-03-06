<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="Cms_Users", indexes={@ORM\Index(name="username", columns={"username"}), @ORM\Index(name="email", columns={"email"}), @ORM\Index(name="avatar_id", columns={"avatar_id"}), @ORM\Index(name="user_deleted", columns={"user_deleted"})})
 * @ORM\Entity(repositoryClass="Cms\Repository\User")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email = '';

    /**
     * @var string
     *
     * @ORM\Column(name="userpass", type="string", length=100, nullable=false)
     */
    private $userpass = '';

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=false)
     */
    private $displayName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="forename", type="string", length=45, nullable=false)
     */
    private $forename = '';

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=false)
     */
    private $surname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=100, nullable=false)
     */
    private $location = '';

    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string", length=100, nullable=false)
     */
    private $occupation = '';

    /**
     * @var string
     *
     * @ORM\Column(name="interests", type="text", length=65535, nullable=false)
     */
    private $interests;

    /**
     * @var string
     *
     * @ORM\Column(name="homepage_link", type="string", length=150, nullable=false)
     */
    private $homepageLink = '';

    /**
     * @var string
     *
     * @ORM\Column(name="homepage_text", type="string", length=100, nullable=false)
     */
    private $homepageText = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="avatar_id", type="integer", nullable=false)
     */
    private $avatarId = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="join_date", type="datetime", nullable=false)
     */
    private $joinDate = '0000-00-00 00:00:00';

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=20, nullable=false)
     */
    private $ipAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="user_groups", type="text", length=65535, nullable=false)
     */
    private $userGroups;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_key", type="string", length=64, nullable=false)
     */
    private $activationKey = '';

    /**
     * @var string
     *
     * @ORM\Column(name="seo_username", type="string", length=100, nullable=false)
     */
    private $seoUsername = '';

    /**
     * @var string
     *
     * @ORM\Column(name="user_deleted", type="string", length=1, nullable=false)
     */
    private $userDeleted = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="user_moderate", type="string", length=1, nullable=false)
     */
    private $userModerate = 'Y';

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUserpass()
    {
        return $this->userpass;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getForename()
    {
        return $this->forename;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getOccupation()
    {
        return $this->occupation;
    }

    public function getInterests()
    {
        return $this->interests;
    }

    public function getHomepageLink()
    {
        return $this->homepageLink;
    }

    public function getHomepageText()
    {
        return $this->homepageText;
    }

    public function getAvatarId()
    {
        return $this->avatarId;
    }

    public function getJoinDate()
    {
        return $this->joinDate;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function getUserGroups()
    {
        return $this->userGroups;
    }

    public function getActivationKey()
    {
        return $this->activationKey;
    }

    public function getSeoUsername()
    {
        return $this->seoUsername;
    }

    public function getUserDeleted()
    {
        return $this->userDeleted;
    }

    public function getUserModerate()
    {
        return $this->userModerate;
    }
}

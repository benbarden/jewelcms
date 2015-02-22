<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSession
 *
 * @ORM\Table(name="Cms_UserSessions", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="session_id", columns={"session_id"})})
 * @ORM\Entity(repositoryClass="Cms\Repository\UserSession")
 */
class UserSession
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
     * @ORM\Column(name="session_id", type="string", length=64, nullable=false)
     */
    private $sessionId = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=20, nullable=false)
     */
    private $ipAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="text", length=65535, nullable=false)
     */
    private $userAgent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="login_date", type="datetime", nullable=false)
     */
    private $loginDate = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=false)
     */
    private $expiryDate = '0000-00-00 00:00:00';


}

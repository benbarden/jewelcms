<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccessLog
 *
 * @ORM\Table(name="Cms_AccessLog", indexes={@ORM\Index(name="tag", columns={"tag"}), @ORM\Index(name="ip_address", columns={"ip_address"})})
 * @ORM\Entity
 */
class AccessLog
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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text", length=65535, nullable=false)
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=100, nullable=false)
     */
    private $tag;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="log_date", type="datetime", nullable=false)
     */
    private $logDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=20, nullable=false)
     */
    private $ipAddress;


}

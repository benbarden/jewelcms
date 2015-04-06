<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRole
 *
 * @ORM\Table(name="Cms_UserRoles", indexes={@ORM\Index(name="is_admin", columns={"is_admin"}), @ORM\Index(name="is_default", columns={"is_default"})})
 * @ORM\Entity(repositoryClass="Cms\Repository\UserRole")
 */
class UserRole
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="is_admin", type="string", length=1, nullable=false)
     */
    private $isAdmin = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="is_default", type="string", length=1, nullable=false)
     */
    private $isDefault = 'N';


}

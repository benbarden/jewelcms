<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 *
 * @ORM\Table(name="Cms_Settings", indexes={@ORM\Index(name="preference", columns={"preference"})})
 * @ORM\Entity(repositoryClass="Cms\Repository\Setting")
 */
class Setting
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
     * @ORM\Column(name="preference", type="string", length=45, nullable=false)
     */
    private $preference = '';

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     */
    private $content;


}

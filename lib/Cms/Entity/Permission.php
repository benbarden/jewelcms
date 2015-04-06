<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permission
 *
 * @ORM\Table(name="Cms_Permissions", indexes={@ORM\Index(name="is_system", columns={"is_system"})})
 * @ORM\Entity(repositoryClass="Cms\Repository\Permission")
 */
class Permission
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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="is_system", type="string", length=1, nullable=false)
     */
    private $isSystem;

    /**
     * @var string
     *
     * @ORM\Column(name="create_article", type="text", length=65535, nullable=false)
     */
    private $createArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="publish_article", type="text", length=65535, nullable=false)
     */
    private $publishArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="edit_article", type="text", length=65535, nullable=false)
     */
    private $editArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="delete_article", type="text", length=65535, nullable=false)
     */
    private $deleteArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="attach_file", type="text", length=65535, nullable=false)
     */
    private $attachFile;


}

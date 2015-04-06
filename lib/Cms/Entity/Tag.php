<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="Cms_Tags", indexes={@ORM\Index(name="tag", columns={"tag"}), @ORM\Index(name="tag_count", columns={"tag_count"})})
 * @ORM\Entity(repositoryClass="Cms\Repository\Tag")
 */
class Tag
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
     * @ORM\Column(name="tag", type="string", length=100, nullable=false)
     */
    private $tag = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="tag_count", type="integer", nullable=false)
     */
    private $tagCount = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="article_list", type="text", length=65535, nullable=false)
     */
    private $articleList;


}

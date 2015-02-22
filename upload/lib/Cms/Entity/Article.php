<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="Cms_Content", indexes={@ORM\Index(name="author_id", columns={"author_id"}), @ORM\Index(name="category_id", columns={"category_id"}), @ORM\Index(name="content_status", columns={"content_status"}), @ORM\Index(name="permalink", columns={"permalink"}), @ORM\Index(name="title", columns={"title"}), @ORM\Index(name="content", columns={"content"}), @ORM\Index(name="title_content", columns={"title", "content"})})
 * @ORM\Entity(repositoryClass="Cms\Repository\Article")
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=125, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=255, nullable=false)
     */
    private $permalink;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="author_id", type="integer", nullable=false)
     */
    private $authorId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true)
     */
    private $categoryId = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updated", type="datetime", nullable=false)
     */
    private $lastUpdated = '0000-00-00 00:00:00';

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="text", length=65535, nullable=false)
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="link_url", type="string", length=150, nullable=false)
     */
    private $linkUrl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="content_status", type="string", length=20, nullable=false)
     */
    private $contentStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="tags_deleted", type="text", length=65535, nullable=false)
     */
    private $tagsDeleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="article_order", type="integer", nullable=false)
     */
    private $articleOrder = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="article_excerpt", type="text", length=65535, nullable=false)
     */
    private $articleExcerpt;


}

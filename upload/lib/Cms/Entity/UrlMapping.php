<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UrlMapping
 *
 * @ORM\Table(name="Cms_UrlMapping", uniqueConstraints={@ORM\UniqueConstraint(name="relative_url", columns={"relative_url"})}, indexes={@ORM\Index(name="is_active", columns={"is_active"}), @ORM\Index(name="article_id", columns={"article_id"}), @ORM\Index(name="category_id", columns={"category_id"})})
 * @ORM\Entity
 */
class UrlMapping
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
     * @ORM\Column(name="relative_url", type="string", length=255, nullable=false)
     */
    private $relativeUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="is_active", type="string", length=1, nullable=false)
     */
    private $isActive = 'Y';

    /**
     * @var integer
     *
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     */
    private $articleId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $categoryId = '0';


}

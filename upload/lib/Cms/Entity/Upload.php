<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Upload
 *
 * @ORM\Table(name="Cms_Uploads", indexes={@ORM\Index(name="file_area_id", columns={"file_area_id"}), @ORM\Index(name="author_id", columns={"author_id"}), @ORM\Index(name="hits", columns={"hits"}), @ORM\Index(name="is_avatar", columns={"is_avatar"}), @ORM\Index(name="is_siteimage", columns={"is_siteimage"}), @ORM\Index(name="article_id", columns={"article_id"})})
 * @ORM\Entity
 */
class Upload
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="text", length=65535, nullable=false)
     */
    private $location;

    /**
     * @var integer
     *
     * @ORM\Column(name="file_area_id", type="integer", nullable=false)
     */
    private $fileAreaId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="author_id", type="integer", nullable=false)
     */
    private $authorId = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="edit_date", type="datetime", nullable=false)
     */
    private $editDate = '0000-00-00 00:00:00';

    /**
     * @var integer
     *
     * @ORM\Column(name="hits", type="integer", nullable=false)
     */
    private $hits = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="is_avatar", type="string", length=1, nullable=false)
     */
    private $isAvatar = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="is_siteimage", type="string", length=1, nullable=false)
     */
    private $isSiteimage = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="delete_flag", type="string", length=1, nullable=false)
     */
    private $deleteFlag = '';

    /**
     * @var string
     *
     * @ORM\Column(name="thumb_small", type="text", length=65535, nullable=false)
     */
    private $thumbSmall;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb_medium", type="text", length=65535, nullable=false)
     */
    private $thumbMedium;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb_large", type="text", length=65535, nullable=false)
     */
    private $thumbLarge;

    /**
     * @var string
     *
     * @ORM\Column(name="upload_size", type="string", length=50, nullable=false)
     */
    private $uploadSize = '';

    /**
     * @var string
     *
     * @ORM\Column(name="seo_title", type="string", length=100, nullable=false)
     */
    private $seoTitle = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     */
    private $articleId = '0';


}

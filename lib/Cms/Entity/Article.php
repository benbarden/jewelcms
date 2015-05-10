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
    const STATUS_PUBLISHED = 'Published';
    const STATUS_AUTOSAVE = 'Autosave';
    const STATUS_DRAFT = 'Draft';
    const STATUS_REVIEW = 'Review';
    const STATUS_SCHEDULED = 'Scheduled';
    const STATUS_PRIVATE = 'Private';
    const STATUS_DELETED = 'Deleted';

    // *** ASSOCIATIONS *** //
    /**
     * @ORM\OneToOne(targetEntity="Cms\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @ORM\OneToOne(targetEntity="Cms\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     **/
    private $author;

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getPermalink()
    {
        return $this->permalink;
    }

    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getLinkUrl()
    {
        return $this->linkUrl;
    }

    public function setLinkUrl($linkUrl)
    {
        $this->linkUrl = $linkUrl;
    }

    public function getStatus()
    {
        return $this->contentStatus;
    }

    public function isPublished()
    {
        return $this->contentStatus == self::STATUS_PUBLISHED;
    }

    public function setStatus($status)
    {
        $this->contentStatus = $status;
    }

    public function setStatusPublished()
    {
        $this->contentStatus = self::STATUS_PUBLISHED;
    }

    public function setStatusAutosave()
    {
        $this->contentStatus = self::STATUS_AUTOSAVE;
    }

    public function setStatusDraft()
    {
        $this->contentStatus = self::STATUS_DRAFT;
    }

    public function setStatusReview()
    {
        $this->contentStatus = self::STATUS_REVIEW;
    }

    public function setStatusScheduled()
    {
        $this->contentStatus = self::STATUS_SCHEDULED;
    }

    public function setStatusPrivate()
    {
        $this->contentStatus = self::STATUS_PRIVATE;
    }

    public function setStatusDeleted()
    {
        $this->contentStatus = self::STATUS_DELETED;
    }

    public function getTagsDeleted()
    {
        return $this->tagsDeleted;
    }

    public function setTagsDeleted($tagsDeleted)
    {
        $this->tagsDeleted = $tagsDeleted;
    }

    public function getArticleOrder()
    {
        return $this->articleOrder;
    }

    public function setArticleOrder($order)
    {
        $this->articleOrder = $order;
    }

    public function getExcerpt()
    {
        return $this->articleExcerpt;
    }

    public function setExcerpt($excerpt)
    {
        $this->articleExcerpt = $excerpt;
    }
}

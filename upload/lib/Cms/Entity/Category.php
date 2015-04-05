<?php

namespace Cms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="Cms_Categories")
 * @ORM\Entity(repositoryClass="Cms\Repository\Category")
 */
class Category
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
     * @ORM\Column(name="permalink", type="string", length=255, nullable=false)
     */
    private $permalink;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="items_per_page", type="integer", nullable=false)
     */
    private $itemsPerPage;

    /**
     * @var string
     *
     * @ORM\Column(name="sort_rule", type="string", length=30, nullable=false)
     */
    private $sortRule;

    // Dynamic fields - not in db
    private $sortRuleField;
    private $sortRuleDirection;

    public function getCategoryId()
    {
        return $this->id;
    }

    public function setCategoryId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPermalink()
    {
        return $this->permalink;
    }

    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    public function setItemsPerPage($perPage)
    {
        $this->itemsPerPage = $perPage;
    }

    public function getSortRule()
    {
        return $this->sortRule;
    }

    public function setSortRule($sortRule)
    {
        $this->sortRule = $sortRule;
    }

    private function populateSortRuleData()
    {
        if ($this->sortRule) {
            $sortRuleArray = explode("|", $this->sortRule);
            $this->sortRuleField = $sortRuleArray[0];
            $this->sortRuleDirection = $sortRuleArray[1];
        } else {
            $this->sortRuleField = "create_date";
            $this->sortRuleDirection = "DESC";
        }
    }

    public function getSortRuleField()
    {
        $this->populateSortRuleData();
        return $this->sortRuleField;
    }

    public function getSortRuleDirection()
    {
        $this->populateSortRuleData();
        return $this->sortRuleDirection;
    }
}

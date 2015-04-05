<?php


namespace Cms\Access;

use Cms\Core\Di\Container;
use Cms\Entity\User;
use Cms\Repository\Permission as RepoPermission;


class Permission
{
    /**
     * @var RepoPermission
     */
    private $permission;

    /**
     * @var User
     */
    private $user;

    /**
     * @param RepoPermission $permission
     * @param User $user
     * @return void
     */
    public function __construct(RepoPermission $permission, User $user = null)
    {
        $this->permission = $permission;
        if ($user) {
            $this->user = $user;
        }
    }

    public function __destruct()
    {
        unset($this->permission);
        unset($this->user);
    }

    private function userGroupMatch($allowedGroups)
    {
        if (!$this->user) return false;
        if (!$allowedGroups) return false;

        $userGroupsArray = explode("|", $this->user->getUserGroups());
        $allowedGroupsArray = explode("|", $allowedGroups);
        $isMatch = false;

        foreach ($userGroupsArray as $ug) {
            foreach ($allowedGroupsArray as $ag) {
                if ($ug == $ag) {
                    $isMatch = true;
                    break;
                }
            }
        }

        return $isMatch;
    }

    public function canCreateArticle()
    {
        $userGroups = $this->permission->getCreateArticle();
        if (!$userGroups) return false;
        return $this->userGroupMatch($userGroups['createArticle']);
    }

    public function canPublishArticle()
    {
        $userGroups = $this->permission->getPublishArticle();
        if (!$userGroups) return false;
        return $this->userGroupMatch($userGroups['publishArticle']);
    }

    public function canEditAllArticles()
    {
        $userGroups = $this->permission->getEditArticle();
        if (!$userGroups) return false;
        return $this->userGroupMatch($userGroups['editArticle']);
    }

    public function canDeleteArticle()
    {
        $userGroups = $this->permission->getDeleteArticle();
        if (!$userGroups) return false;
        return $this->userGroupMatch($userGroups['deleteArticle']);
    }

    public function canAttachFile()
    {
        $userGroups = $this->permission->getAttachFile();
        if (!$userGroups) return false;
        return $this->userGroupMatch($userGroups['attachFile']);
    }
} 
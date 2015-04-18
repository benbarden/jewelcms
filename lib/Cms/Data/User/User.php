<?php


namespace Cms\Data\User;


class User
{
    private $userId;
    private $displayName;
    private $userGroups;

    public function __construct($dbData)
    {
        $this->userId = $dbData['id'];
        $this->displayName = $dbData['display_name'];
        $this->userGroups = $dbData['user_groups'];
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getUserGroups()
    {
        return $this->userGroups;
    }
} 
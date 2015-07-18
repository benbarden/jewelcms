<?php


namespace Cms\Access;

use Cms\Entity\User;


class Login
{
    /**
     * @var User
     */
    private $loggedInUser;

    public function getCookie()
    {
        return isset($_COOKIE['IJ-Login']) ? $_COOKIE['IJ-Login'] : null;
    }

    public function setLoggedInUser(User $user)
    {
        $this->loggedInUser = $user;
    }

    public function getLoggedInUser()
    {
        return $this->loggedInUser;
    }
}
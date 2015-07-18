<?php


namespace Cms\Ia\Link;

use Cms\Entity\User;


class UserLink extends Base
{
    /**
     * @var User
     */
    private $user;

    public function __destruct()
    {
        unset($this->user);
        parent::__destruct();
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    private function getOptimisedUserUrl()
    {
        return $this->optimiser->optimise($this->user->getDisplayName());
    }

    /**
     * index.php/profile/1/ben/
     * @return string
     */
    protected function generateLinkStyleClassic()
    {
        return URL_ROOT.sprintf('index.php/profile/%s/%s/',
            $this->user->getId(), $this->getOptimisedUserUrl());
    }

    /**
     * user/1/ben/
     * NOTE: to distinguish between other content, styles 2-5 are identical
     * @return string
     */
    protected function generateLinkStyleLong()
    {
        return URL_ROOT.sprintf('profile/%s/%s/',
            $this->user->getId(), $this->getOptimisedUserUrl());
    }

    /**
     * @return string
     */
    protected function generateLinkStyleTitleOnly()
    {
        return $this->generateLinkStyleLong();
    }

    /**
     * @return string
     */
    protected function generateLinkStyleCategoryAndTitle()
    {
        return $this->generateLinkStyleLong();
    }

    /**
     * @return string
     */
    protected function generateLinkStyleDateAndTime()
    {
        return $this->generateLinkStyleLong();
    }
}
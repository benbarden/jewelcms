<?php


namespace Cms\Core\Di;

use Cms\Exception\Core\Di\ServiceLocatorException;


class ServiceLocator implements IServiceLocator
{
    // *** CORE METHODS (GENERIC) *** //
    protected $services = array();

    public function set($name, $service)
    {
        if (!is_object($service)) {
            throw new ServiceLocatorException("ServiceLocator only supports objects.");
        }
        if (!in_array($service, $this->services, true)) {
            $this->services[$name] = $service;
        }
        return $this;
    }

    public function get($name)
    {
        if (!isset($this->services[$name])) {
            throw new ServiceLocatorException("The service $name has not been registered.");
        }
        return $this->services[$name];
    }

    public function has($name)
    {
        return isset($this->services[$name]);
    }

    public function remove($name)
    {
        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
        return $this;
    }

    public function clear()
    {
        $this->services = array();
        return $this;
    }

    // *** SERVICE KEYS *** //
    const KEY_CMS_CONFIG = 'Cms.Config';
    const KEY_CMS_ENTITY_MANAGER = 'Cms.EntityManager';
    const KEY_CMS_THEME_ENGINE = 'Cms.ThemeEngine';

    const KEY_AUTH_CURRENT_USER = 'Auth.CurrentUser';

    const KEY_IA_LINK_AREA = 'IA.LinkArea';
    const KEY_IA_LINK_ARTICLE = 'IA.LinkArticle';

    // *** HELPER METHODS (SPECIFIC) *** //

    /**
     * @param Config $config
     */
    public function setCmsConfig(Config $config)
    {
        $this->set(self::KEY_CMS_CONFIG, $config);
    }

    /**
     * @return Config $config
     */
    public function getCmsConfig()
    {
        return $this->get(self::KEY_CMS_CONFIG);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setCmsEntityManager(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->set(self::KEY_CMS_ENTITY_MANAGER, $entityManager);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getCmsEntityManager()
    {
        return $this->get(self::KEY_CMS_ENTITY_MANAGER);
    }

    /**
     * @param \Cms\Theme\Engine $themeEngine
     */
    public function setCmsThemeEngine(\Cms\Theme\Engine $themeEngine)
    {
        $this->set(self::KEY_CMS_THEME_ENGINE, $themeEngine);
    }

    /**
     * @return \Cms\Theme\Engine
     */
    public function getCmsThemeEngine()
    {
        return $this->get(self::KEY_CMS_THEME_ENGINE);
    }

    /**
     * @param \Cms\Entity\User $user
     */
    public function setAuthCurrentUser(\Cms\Entity\User $user)
    {
        $this->set(self::KEY_AUTH_CURRENT_USER, $user);
    }

    /**
     * @return \Cms\Entity\User
     */
    public function getAuthCurrentUser()
    {
        try {
            return $this->get(self::KEY_AUTH_CURRENT_USER);
        } catch (ServiceLocatorException $e) {
            return null;
        }
    }

    /**
     * @param \Cms\Ia\Link\ArticleLink $articleLink
     */
    public function setIALinkArticle(\Cms\Ia\Link\ArticleLink $articleLink)
    {
        $this->set(self::KEY_IA_LINK_ARTICLE, $articleLink);
    }

    /**
     * @return \Cms\Ia\Link\ArticleLink
     */
    public function getIALinkArticle()
    {
        return $this->get(self::KEY_IA_LINK_ARTICLE);
    }

    /**
     * @param \Cms\Ia\Link\AreaLink $areaLink
     */
    public function setIALinkArea(\Cms\Ia\Link\AreaLink $areaLink)
    {
        $this->set(self::KEY_IA_LINK_AREA, $areaLink);
    }

    /**
     * @return \Cms\Ia\Link\AreaLink
     */
    public function getIALinkArea()
    {
        return $this->get(self::KEY_IA_LINK_AREA);
    }
}
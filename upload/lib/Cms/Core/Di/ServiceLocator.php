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
    const KEY_AUTH_CURRENT_USER = 'Auth.CurrentUser';

    // *** HELPER METHODS (SPECIFIC) *** //

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
} 
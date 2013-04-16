<?php
/**
 * менеджер модулей, отдаёт нам объект модуля по имени модуля
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Module_Manager
{
    protected $_modules = array();
    protected $_names = array();

    public function getNames()
    {
        return $this->_names;
    }

    public function setNames($names)
    {
        $this->_names = $names;
    }


    /**
     * @param string $name
     *
     * @return Whale_Module|Whale_Module_App
     * @throws Zend_Exception
     */
    public function get($name)
    {
        /**
         * получали ли инстанс модуля? если нет, пытаемся инстанциировать и положить в $this->_modules для того,
         * чтобы в следующий раз взять оттуда
         */
        if (!array_key_exists($name, $this->_modules)) {
            if (in_array($name, $this->getNames())) {
                $className = ucfirst($name) . "_Model_Module"; // например, Housing_Model_Module
                if (class_exists($className)) {
                    /** @var $module Whale_Module|Whale_Module_App */
                    $module = new $className;
                    $this->_modules[$name] = $module;
                } else {
                    throw new Zend_Exception('module ' . $name . ' doesnt exist (class ' . $className . ' not found)');
                }
            } else {
                throw new Zend_Exception('module ' . $name . ' doesnt exist (available: ' . print_r($this->getNames()) . ')');
            }
        } else {
            /** @var $module Whale_Module|Whale_Module_App */
            $module = $this->_modules[$name];
        }

        return $module;
    }

    /**
     * @return Whale_Module[]|Whale_Module_App[]
     */
    public function getAll()
    {
        foreach ($this->getNames() as $name) {
            $this->get($name);
        }

        return $this->_modules;
    }

    // singleton (i know)

    protected static $instance = null;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @return Whale_Module_Manager
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
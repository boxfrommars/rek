<?php
/**
 * Класс модуля, от него наследуются все классы модулей системы. с помощью этих классов мы получаем
 * доступ ко всей информации о модуле (его роуты, навигацию, привилегии), узнаём, является ли модуль приложением
 * находится ли модуль в разработке, ну и всякое такое
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Module
{

    protected $_name = null;
    protected $_title = null;
    protected $_isApp = false; // является ли модуль приложением
    protected $_isEnabled = true; // активирован ли он (или находится в разработке)
    protected $_options = array(); // все остальные опции

    protected $_resources = array('index'); // список ресурсов, ресурсы == контроллеры

    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * устанавливает опции для данного модуля
     * @param array $options
     */
    public function setOptions($options = array())
    {
        foreach ($options as $key => $value) { // если опция -- name или enabled -- делаем что-нибудь изощрённое
            switch ($key) {
                case 'name':
                    $this->_name = $value;
                case 'isEnabled':
                    $this->_isEnabled = (bool)$value;
                    break;
                default:
                    break;
            }
        }
        $this->_options = array_merge($this->_options, $options); // в остальных случаях -- просто запихиваем в опции
    }

    /**
     * является ли модуль приложением
     * @return bool
     */
    public function isApp()
    {
        return $this->_isApp;
    }

    /**
     * активный ли модуль (или в разработке)
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_isEnabled;
    }

    /**
     * возвращает навигацию для этого модуля  для последующего прикрепления к Zend_Navigation
     *
     * @return array
     */
    public function getNavigation()
    {
        return array();
    }

    public function getResources()
    {
        $moduleName = $this->getName();

        return array_map(function ($resourceName) use ($moduleName) {
            return $moduleName . ':' . $resourceName;
        }, $this->_resources);
    }

    /**
     * Возвращает ACL для этого модуля, доступную для всех пользователей
     *
     * @return array $navigation
     */
    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'user', 'resource' => $this->getName() . ':index', 'action' => null)
        );
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getTitle()
    {
        return $this->_title;
    }

}
<?php
/**
 * @project UEC
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Default_Model_Module extends Whale_Module
{
    protected $_name = 'default';
    protected $_resources = array('index', 'error');

    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'guest', 'resource' => $this->getName() . ':index', 'action' => null),
            array('type' => 'allow', 'role' => 'guest', 'resource' => $this->getName() . ':error', 'action' => null),
        );
    }

    public function getNavigation()
    {
        return array(
            array(
                'label' => 'Главная',
                'module' => 'default',
                'controller' => 'index',
                'action' => 'index',
                'extendedAs' => 'main', // немного магии, определяем имя для подключения навигации из модулей (или откуда угодно)
                'pages' => array(
                    array(
                        'label' => 'Войти',
                        'route' => 'login',
                        'visible' => false,
                    ),
                ),
            ),
        );
    }
}
<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class News_Model_Module extends Whale_Module
{
    protected $_name = 'news';
    protected $_resources = array('index');

    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'guest', 'resource' => $this->getName() . ':index', 'action' => null),
        );
    }

    public function getNavigation()
    {
        return array(
            'main' => array(
                array(
                    'label' => 'Новости',
                    'module' => 'news',
                    'route' => 'module',
                    'pages' => array(
                        array(
                            'module' => 'news',
                            'controller' => 'index',
                            'action' => 'view',
                            'label' => 'Подробности',
                            'visible' => false
                        )
                    ),
                ),
            )
        );
    }
}
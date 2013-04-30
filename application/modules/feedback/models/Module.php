<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Feedback_Model_Module extends Whale_Module
{

    protected $_name = 'faq';
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
                    'label' => 'Вопросы и ответы',
                    'module' => 'faq',
                    'route' => 'module',
                ),
            )
        );
    }
}
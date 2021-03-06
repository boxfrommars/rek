<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Page_Model_Module extends Whale_Module
{

    protected $_name = 'page';
    protected $_resources = array('index');

    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'guest', 'resource' => $this->getName() . ':index', 'action' => null),
        );
    }
}
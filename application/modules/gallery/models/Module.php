<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Gallery_Model_Module extends Whale_Module
{

    protected $_name = 'gallery';
    protected $_resources = array('index');

    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'guest', 'resource' => $this->getName() . ':index', 'action' => null),
        );
    }
}
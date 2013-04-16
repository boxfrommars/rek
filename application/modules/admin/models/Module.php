<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Model_Module extends Whale_Module
{
    protected $_name = 'admin';
    protected $_resources = array('index', 'news', 'faq', 'page-text', 'catalog', 'category', 'brand', 'collection');

    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':index', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':news', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':faq', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':page-text', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':catalog', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':category', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':brand', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':collection', 'action' => null),
        );
    }
}
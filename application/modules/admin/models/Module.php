<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Model_Module extends Whale_Module
{
    protected $_name = 'admin';
    protected $_resources = array(
        'index', 'system', 'news', 'faq', 'page-text', 'product', 'product-color', 'category',
        'brand', 'collection', 'country', 'color', 'surface', 'thesaurus', 'gallery', 'page',
    );

    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':index', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':news', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':faq', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':page-text', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':product', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':product-color', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':category', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':brand', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':collection', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':system', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':country', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':color', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':surface', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':thesaurus', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':gallery', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':page', 'action' => null),
        );
    }
}
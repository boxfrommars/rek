<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Model_Module extends Whale_Module
{
    protected $_name = 'admin';
    protected $_resources = array(
        'index', 'system', 'news', 'feedback', 'page-text', 'product', 'product-color', 'product-decor', 'product-inter', 'category',
        'brand', 'collection', 'country', 'color', 'surface', 'pattern', 'thesaurus', 'gallery', 'gallerymain', 'page', 'settings', 'rek'
    );

    public function getPrivileges()
    {
        return array(
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':index', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':news', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':feedback', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':page-text', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':product', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':product-color', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':product-decor', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':product-inter', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':category', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':brand', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':collection', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':system', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':country', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':color', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':surface', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':pattern', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':thesaurus', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':gallery', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':gallerymain', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':page', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':settings', 'action' => null),
            array('type' => 'allow', 'role' => 'admin', 'resource' => $this->getName() . ':rek', 'action' => null),
        );
    }
}
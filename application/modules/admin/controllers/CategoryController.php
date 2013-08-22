<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_CategoryController extends Whale_Controller_Action_Admin_Page
{

    protected $_redirectOptions = array('action' => 'index', 'controller' => 'category');
    protected $_redirectRouteName = 'admin';

    protected $_order = array('order ASC');

    protected $_perPage = null;

    /**
     * @var Catalog_Model_CategoryService
     */
    protected $_model;

    /**
     * @var Admin_Form_CatalogItem
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_CategoryService();
        $this->_form = new Admin_Form_CatalogItem();
    }
}


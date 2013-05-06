<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_BrandController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var Catalog_Model_BrandService
     */
    protected $_model;

    /**
     * @var Admin_Form_CatalogItem
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_BrandService();
        $this->_form = new Admin_Form_CatalogItem();
    }
}


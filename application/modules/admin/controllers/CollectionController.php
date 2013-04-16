<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_CollectionController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var Catalog_Model_CollectionService
     */
    protected $_model;

    /**
     * @var Admin_Form_Collection
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_CollectionService();
        $this->_form = new Admin_Form_Collection();
    }
}


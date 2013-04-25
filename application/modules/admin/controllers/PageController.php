<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_PageController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var Catalog_Model_CategoryService
     */
    protected $_model;

    /**
     * @var Admin_Form_Category
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Page_Model_Service();
        $this->_form = new Admin_Form_Page();
    }
}


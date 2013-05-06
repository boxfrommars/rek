<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_PageController extends Whale_Controller_Action_Admin_Page
{

    protected $_order = array('path ASC');

    /**
     * @var Catalog_Model_CategoryService
     */
    protected $_model;

    /**
     * @var Whale_Form_Page
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Page_Model_Service();
        $this->_form = new Whale_Form_Page();
    }
}


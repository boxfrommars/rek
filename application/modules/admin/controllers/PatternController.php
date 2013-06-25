<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_PatternController extends Whale_Controller_Action_Admin_Article
{

    protected $_order = null;

    /**
     * @var Catalog_Model_PatternService
     */
    protected $_model;

    /**
     * @var Whale_Form_Titled
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_PatternService();
        $this->_form = new Whale_Form_Titled();
    }
}


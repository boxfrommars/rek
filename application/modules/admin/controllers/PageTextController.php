<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_PageTextController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var Whale_PageText_Service
     */
    protected $_model;
    protected $_order = array('created_at ASC');

    /**
     * @var Whale_PageText_Form
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Whale_PageText_Service();
        $this->_form = new Whale_PageText_Form();
    }
}


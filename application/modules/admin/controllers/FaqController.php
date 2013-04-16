<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_FaqController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var Faq_Model_Service
     */
    protected $_model;
    protected $_order = array('created_at ASC');

    /**
     * @var Admin_Form_Faq
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Faq_Model_Service();
        $this->_form = new Admin_Form_Faq();
    }
}


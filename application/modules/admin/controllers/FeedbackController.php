<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_FeedbackController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var Feedback_Model_Service
     */
    protected $_model;
    protected $_order = array('created_at ASC');

    /**
     * @var Admin_Form_Feedback
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Feedback_Model_Service();
        $this->_form = new Admin_Form_Feedback();
    }
}


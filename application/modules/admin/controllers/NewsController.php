<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_NewsController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var News_Model_Service
     */
    protected $_model;
    protected $_order = array('published_at DESC');

    /**
     * @var Admin_Form_News
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new News_Model_Service();
        $this->_form = new Admin_Form_News();
    }
}


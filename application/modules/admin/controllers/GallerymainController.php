<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_GallerymainController extends Whale_Controller_Action_Admin_Article
{

    protected $_order = null;

    /**
     * @var Gallery_Model_ServiceMain
     */
    protected $_model;

    /**
     * @var Admin_Form_Gallerymain
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Gallery_Model_ServiceMain();
        $this->_form = new Admin_Form_Gallerymain();
    }
}


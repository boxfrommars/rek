<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Gallery_IndexController extends Whale_Controller_Action
{
    /**
     * @var Gallery_Model_Service
     */
    protected $_service;

    public function init()
    {
        parent::init();
        $this->_service = new Gallery_Model_Service();
    }

    public function indexAction()
    {
        $this->_setPage('gallery');
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('gallery');
        $this->view->items = $this->_service->fetchAll(array('is_published = ?' => true), array('created_at ASC'));
    }
}


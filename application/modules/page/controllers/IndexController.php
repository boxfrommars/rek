<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Page_IndexController extends Whale_Controller_Action
{
    /**
     * @var Gallery_Model_Service
     */
    protected $_service;

    public function init()
    {
        parent::init();
        $this->_service = new Page_Model_Service();
    }

    public function viewAction()
    {
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('page');
        $this->view->assign($this->getParam('page'));
        $this->view->page = new Whale_Page_SeoItemAdapter($this->getParam('page'));
        Whale_Log::log($this->getAllParams());
    }
}


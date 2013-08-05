<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Page_IndexController extends Whale_Controller_Action
{
    /**
     * @var Page_Model_Service
     */
    protected $_service;

    public function init()
    {
        parent::init();
        $this->_service = new Page_Model_Service();
    }

    public function viewAction()
    {
        $page = $this->getParam('page');
        $this->_setPage($page['name']);
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('page');
        $this->view->assign($this->getParam('page'));
        if ($page['entity'] === 'page' && !empty($page['parents'][1]['id'])) {
            $pagesMenuItems = $this->_service->getBaseSelect()->where('p.entity = ?', 'page')->where('p.id_parent = ?', $page['parents'][1]['id'])->where('p.is_published')->query()->fetchAll();
            Whale_Log::log($pagesMenuItems);
            $this->view->pagesMenuItems = $pagesMenuItems;
        }
    }
}


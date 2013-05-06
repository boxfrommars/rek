<?php
/**
 * @copyright (c) 2013
 * @author    Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Action extends Zend_Controller_Action
{
    /**
     * @var Whale_User
     */
    protected $_user;
    /**
     * @var Zend_Log
     */
    protected $_log;
    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;

    public function init()
    {
        parent::init();
        $this->_user = Whale_User_Current::get();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        /** @var Zend_Controller_Action_Helper_FlashMessenger $flashMessenger */
        $flashMessenger = $this->_flashMessenger;
        $this->view->assign('flashMessages', $flashMessenger->getMessages());
        Whale_Log::log($this->getRequest()->getParams());
    }

    /**
     * @return Zend_Controller_Request_Http
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    /**
     * @param $name
     */
    protected function _setPage($name)
    {
        $pageService = new Whale_Node_Service();
        $pages = $pageService->get('Top');
        Whale_Log::log($name);

        $page = count($pages) > 0 ? new Whale_Page_SeoItemAdapter(array_pop($pages)) : new Whale_Page_SeoItemAdapter(array());
//        $db = Zend_Db_Table::getDefaultAdapter();
//        $page = $db->select()->from('page', array('*'))->where('name = ?', $name)->query()->fetch();
        $this->view->assign('page', $page);
    }
}
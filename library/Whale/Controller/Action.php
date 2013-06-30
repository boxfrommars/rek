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

    protected $_settings;
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


        $settings = array();
        $settingsService = new Admin_Model_Settings();
        $settingsData = $settingsService->fetchAll()->toArray();
        foreach ($settingsData as $settingsRow) {
            $settings[$settingsRow['name']] = $settingsRow['value'];
        }
        $this->view->settings = $settings;
        $this->settings = $settings;


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
        Whale_Log::log($name);
        $page = $this->_getParam('page');
        if (empty($page)) {

            $pageService = new Page_Model_Service();
            $page = $pageService->getPage(array(
                array('key' => 'name = ?', 'value' => $name,),
            ));
        }

        $this->view->assign('page', new Whale_Page_SeoItemAdapter($page));
    }
}
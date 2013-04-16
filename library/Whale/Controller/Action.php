<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
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
        $this->_log = $this->getLog();
        $this->_user = Whale_User_Current::get();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashMessages = $this->_flashMessenger->getMessages();
        $this->log($this->getRequest()->getParams());
    }

    /**
     * @return bool|Zend_Log
     */
    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');

        return $log;
    }

    public function log($message, $priority = Zend_Log::INFO)
    {
        if (is_scalar($message)) {
            $this->_log->log($message, $priority);
        } else {
            $this->_log->log(print_r($message, true), $priority);
        }

    }
}
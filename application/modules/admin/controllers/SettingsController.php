<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_SettingsController extends Whale_Controller_Action_Admin_Article
{

    /**
     * @var Admin_Model_Settings
     */
    protected $_model;
    protected $_order = array('title DESC');
    protected $_isAddable = false;

    /**
     * @var Admin_Form_Settings
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Admin_Model_Settings();
        $this->_form = new Admin_Form_Settings();
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $value = $this->getParam('price-value');
            if ($value) {
                $this->_model->update(array('value' => $value), array('name = ?' => 'price'));
                $this->_flashMessenger->addMessage('Прайс сохранён');
            } else {
                $this->_flashMessenger->addMessage('Файл не выбраны');
            }
            $this->_redirectToIndex(array('action' => 'index', 'controller' => 'settings'));
        }
        parent::indexAction();
    }
}


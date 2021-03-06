<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Feedback_IndexController extends Whale_Controller_Action_Admin_Article
{
    protected $_isEditable = false;
    protected $_isDeletable = false;
    protected $_isIndexable = false;
    protected $_redirectRouteName = 'feedback';

    /**
     * @var Feedback_Model_Service
     */
    protected $_model;

    public function init()
    {
        parent::init();
        $this->_model = new Feedback_Model_Service();
        $this->_form = new Feedback_Form_Public();

        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('page');
    }
    public function addAction()
    {
        $this->_setPage('feedback');
        parent::addAction();
    }
    protected function _afterAdd($values)
    {
        $session = new Zend_Session_Namespace('lastCatalogPage');
        $values[] = 'последняя просморенная страница каталога: ' . $session->url;

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText(implode("\n\n", $values));
        $mail->setFrom($this->settings['email'], 'Admin');

        $mail->addTo($this->settings['email'], 'Admin');
        $mail->setSubject('Новая заявка на сайте Рекада');
        $mail->send();
        $this->view->thanks = true;
    }
}


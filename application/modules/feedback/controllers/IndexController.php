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
        Whale_Log::log($values);
        Whale_Log::log($this->_settings);
        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText(implode("\n\n", $values));
        $mail->setFrom($this->_settings['email'], 'Админ');

        $mail->addTo($this->_settings['email'], 'Админ');
        $mail->setSubject('Новая заявка на сайте Рекада');
        Whale_Log::log($mail->getParts());
        $mail->send();
    }
}


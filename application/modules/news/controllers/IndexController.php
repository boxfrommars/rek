<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class News_IndexController extends Whale_Controller_Action
{
    /**
     * @var News_Model_Service
     */
    protected $_model;

    public function init()
    {
        parent::init();
        $this->_model = new News_Model_Service();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('page');
    }

    public function indexAction()
    {
        $this->_setPage('news');
        $this->view->news = $this->_model->fetchAll(array('is_published = ?' => true), array('published_at DESC'));
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');
        $item = $this->_model->fetchRow(array('id = ?' => $id, 'is_published = ?' => true));
        $this->view->news = $this->_model->fetchAll(
            array('id != ?' => $id, 'is_published = ?' => true), array('published_at DESC'),
            5
        );
        $this->view->item = $item;
    }

}


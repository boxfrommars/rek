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

        $this->view->serviceName = 'news';
        $this->view->serviceTitle = 'Новости';

        $this->view->menu = array(
            array(
                'title' => 'Новости',
                'url' => '/news',
                'active' => true,
            ),
        );
    }

    public function indexAction()
    {
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


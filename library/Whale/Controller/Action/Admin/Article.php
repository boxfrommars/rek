<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Action_Admin_Article extends Whale_Controller_Action
{
    /**
     * @var Zend_Db_Table_Abstract
     */
    protected $_model;
    protected $_order = array('created_at DESC');
    protected $_perPage = 25;

    /**
     * @var Zend_Form
     */
    protected $_form;

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $page = (int) $this->getParam('page');
        if ($page < 1) {
            throw new Zend_Controller_Action_Exception("Invalid page {$page}", 404);
        }
        $this->view->items = $this->_model->fetchAll(null, $this->_order, $this->_perPage, ($this->getParam('page') - 1) * $this->_perPage);
        Whale_Log::log($this->view->items);
        // @TODO cache
        $count = (int) $this->_model->getAdapter()->fetchOne(
            $this->_model->select()->from($this->_model, 'COUNT(*)')
        );
        Whale_Log::log('index count: ' . $count);

        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Null($count));

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator.phtml');
        $paginator->setDefaultScrollingStyle('Elastic');
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_perPage);
        $paginator->setPageRange(8);
        $this->view->paginator = $paginator;
    }

    public function addAction()
    {
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            if ($this->_form->isValid($request->getPost())) {
                $values = $this->_form->getValues();
                unset ($values['csrf_protect']);
                $this->_model->insert($values);
                $this->_flashMessenger->addMessage('Запись добавлена');

                return $this->_helper->redirector('index');
            }
        }
        $this->view->form = $this->_form;
    }

    public function editAction()
    {
        $id = $this->_getParam('id');
        $item = $this->_model->fetchRow(array('id = ?' => $id));

        if (empty($item)) {
            $this->_flashMessenger->addMessage('Вы пытаетесь отредактировать несуществующую запись');

            return $this->_redirectToIndex();
        }

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            if ($this->_form->isValid($request->getPost())) {
                $values = $this->_form->getValues();
                unset ($values['csrf_protect']);
                $this->_model->update($values, array('id = ?' => $id));
                $this->_flashMessenger->addMessage('Запись сохранена');

                return $this->_redirectToIndex();
            }
        } else {
            $this->_form->populate($item->toArray());
        }

        $this->view->form = $this->_form;
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id');
        $item = $this->_model->fetchRow(array('id = ?' => $id));

        if (true || empty($item)) {
            $this->_flashMessenger->addMessage('В настоящий момент удаление отключено по причинам безопасности');
//            $this->_flashMessenger->addMessage('Вы пытаетесь удалить несуществующую запись');

            return $this->_helper->redirector('index');
        }

        $this->_flashMessenger->addMessage('Запись удалена');
        $this->_model->delete(array('id = ?' => $id));

        return $this->_redirectToIndex();
    }

    protected function _redirectToIndex()
    {
        return $this->_helper->redirector->gotoRoute(array('action' => 'index'), 'admin');
    }
}


<?php
/**
 * @copyright  (c) 2013
 * @author     Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Action_Admin_Article extends Whale_Controller_Action
{
    /**
     * @var Zend_Db_Table_Abstract
     */
    protected $_model;
    protected $_order = array('created_at DESC');
    protected $_perPage = 25;

    protected $_redirectOptions = array('action' => 'index');
    protected $_redirectRouteName = 'admin';

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
        $page = (int)$this->getParam('page');
        if ($page < 1) {
            throw new Zend_Controller_Action_Exception("Invalid page {$page}", 404);
        }
        $this->view->items = $this->_model->fetchAll(
            null,
            $this->_order,
            $this->_perPage,
            ($this->getParam('page') - 1) * $this->_perPage
        );
        // @TODO cache
        $count = (int)$this->_model->getAdapter()->fetchOne(
            $this->_model->select()->from($this->_model, 'COUNT(*)')
        );

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
                $id = $this->_model->insert($values);
                $this->_flashMessenger->addMessage('Запись добавлена');

                return $this->_redirectToIndex(array('id' => $id, 'action' => 'edit'));
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

        $this->view->item = $item;

        if ($this->getRequest()->isPost()) {
            if ($this->_form->isValid($this->getRequest()->getPost())) {
                $values = $this->_form->getValues();
                unset ($values['csrf_protect']);
                $this->_model->update($values, array('id = ?' => $id));
                $this->_flashMessenger->addMessage('Запись сохранена');

                return $this->_redirectToIndex(array('action' => 'edit'));
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
        $this->_setRedirectByItem($item);

        if (empty($item)) {
//            $this->_flashMessenger->addMessage('В настоящий момент удаление отключено по причинам безопасности');
            $this->_flashMessenger->addMessage('Вы пытаетесь удалить несуществующую запись');

            return $this->_redirectToIndex(array('controller' => 'index', 'action' => 'index', 'id' => null));
        }

        $this->_model->delete(array('id = ?' => $id));
        $this->_flashMessenger->addMessage('Запись удалена');

        return $this->_redirectToIndex();
    }

    protected function _setRedirectByItem($item) {}

    protected function _redirectToIndex($routeOptions = null, $routeName = null)
    {
        $routeOptions = $routeOptions ? : $this->_redirectOptions;
        $routeName = $routeName ? : $this->_redirectRouteName;

        return $this->_helper->redirector->gotoRoute($routeOptions, $routeName);
    }
}
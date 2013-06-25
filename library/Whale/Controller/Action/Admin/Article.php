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
     * @var bool разрешено ли добавление записей
     */
    protected $_isAddable = true;
    /**
     * @var bool разрешено ли редактирование
     */
    protected $_isEditable = true;
    /**
     * @var bool разрешено ли удаление
     */
    protected $_isDeletable = true;
    /**
     * @var bool разрешено ли удаление
     */
    protected $_isIndexable = true;
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
        if ($this->_isIndexable) {
            $page = (int)$this->getParam('page');
            if ($page < 1) {
                throw new Zend_Controller_Action_Exception("Invalid page {$page}", 404);
            }
            $this->view->assign('items', $this->_getList($page)
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
            $this->view->assign('paginator', $paginator);
        }
    }

    protected function _getList($page = null)
    {
        return $this->_model->fetchAll(
            null,
            $this->_order,
            $this->_perPage,
            ($page - 1) * $this->_perPage
        );
    }

    public function addAction()
    {
        if ($this->_isAddable) {
            $this->_updateFormByRequest($this->getRequest());
            if ($this->getRequest()->isPost()) {
                $request = $this->getRequest();
                if ($this->_form->isValid($request->getPost())) {
                    $values = $this->_form->getValues();
                    unset ($values['csrf_protect']); // убираем csrf токен
                    $id = $this->_model->insert($values);
                    $this->_afterAdd($values);
                    $this->_flashMessenger->addMessage('Запись добавлена');
                    $this->_setRedirectByItem($values, $id);
                    $this->_redirectTo();
                }
            }
            $this->view->assign('form', $this->_form);
        } else {
            $this->_flashMessenger->addMessage('Вы не можете добавлять сюда элементы');
            $this->_redirectTo();
        }
    }

    public function editAction()
    {
        if ($this->_isEditable) {

            $id = $this->_getParam('id');
            $item = $this->_model->fetchRow(array('id = ?' => $id));
            $this->_updateFormByItem($item);
            $this->_setRedirectByItem($item);
            if (empty($item)) {
                $this->_flashMessenger->addMessage('Вы пытаетесь отредактировать несуществующую запись');
                $this->_redirectTo();
            }

            $this->view->assign('item', $item);

            if ($this->getRequest()->isPost()) {
                if ($this->_form->isValid($this->getRequest()->getPost())) {
                    $values = $this->_form->getValues();
                    unset ($values['csrf_protect']);
                    $this->_model->update($values, array('id = ?' => $id));
                    $this->_flashMessenger->addMessage('Запись сохранена');
                    $this->_redirectTo();
                }
            } else {
                $this->_form->populate($item->toArray());
            }
            $this->view->assign('form', $this->_form);
        } else {
            $this->_flashMessenger->addMessage('Вы не можете редактировать элементы');
            $this->_redirectTo();
        }
    }

    public function deleteAction()
    {
        if ($this->_isDeletable) {
            $id = $this->_getParam('id');
            $item = $this->_model->fetchRow(array('id = ?' => $id));
            $this->_setRedirectByItem($item);

            if (empty($item)) {
//            $this->_flashMessenger->addMessage('В настоящий момент удаление отключено по причинам безопасности');
                $this->_flashMessenger->addMessage('Вы пытаетесь удалить несуществующую запись');

                $this->_redirectTo();
            }

            $this->_beforeDelete($id);
            try {
                $this->_model->delete(array('id = ?' => $id));
                $this->_afterDelete($id);
                $this->_flashMessenger->addMessage('Запись удалена');
            } catch (Exception $e) {
                $this->_flashMessenger->addMessage($e->getMessage());
            }
            $this->_redirectTo();
        } else {
            $this->_flashMessenger->addMessage('Вы не можете удалять элементы');
            $this->_redirectTo();
        }
    }

    /**
     * @param null|array $routeOptions
     * @param null|string $routeName
     */
    protected function _redirectTo($routeOptions = null, $routeName = null)
    {
        $routeOptions = $routeOptions ? : $this->_redirectOptions;
        $routeName = $routeName ? : $this->_redirectRouteName;

        /** @var Zend_Controller_Action_Helper_Redirector $redirector */
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoRoute($routeOptions, $routeName);
    }

    protected function _setRedirectByItem($item, $id = null)
    {
    }

    /**
     * @param $item
     */
    protected function _updateFormByItem($item)
    {

    }

    /**
     * @param $request
     */
    protected function _updateFormByRequest($request)
    {

    }

    protected function _afterAdd($values)
    {

    }

    protected function _beforeDelete($id)
    {

    }

    protected function _afterDelete($id)
    {

    }
}
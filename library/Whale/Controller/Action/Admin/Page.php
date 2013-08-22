<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Controller_Action_Admin_Page extends Whale_Controller_Action_Admin_Article
{
    protected function _updateFormByItem($item)
    {
        if (null !== $item->id_parent) {
            $this->_updateFormByIdParent($item->id_parent);
        }
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     */
    protected function _updateFormByRequest($request)
    {
        $idParent = $request->getParam('idParent');
        if ($idParent) {
            $this->_updateFormByIdParent($idParent);
        }
        $this->view->idParent = $idParent;
    }

    protected function viewAction()
    {
        $id = $this->_getParam('id');
        $this->_helper->viewRenderer('default/admin-view-page', null, true);
        $item = $this->_model->fetchRow(array('id = ?' => $id));
        if (empty($item)) throw new Zend_Controller_Action_Exception('Нет такого', 404);
        $pageService = new Page_Model_Service();
        $subItems = $pageService->fetchAll(array('id_parent = ?' => $id), array('entity', 'order'));
        $this->view->item = $item;
        $this->view->subItems = $subItems;
    }

    /**
     * @param int $idParent
     */
    protected function _updateFormByIdParent($idParent)
    {
        $pageService = new Page_Model_Service();
        $pages = $pageService->fetchAll(array('id = ?' => $idParent))->toArray();
        $options = array();
        foreach ($pages as $page) {
            $options[$page['id']] = $page['title'];
        }
        Whale_Log::log($options);
        /** @var Zend_Form_Element_Multiselect $elm */
        $elm = $this->_form->getElement('id_parent');
        $elm->setMultiOptions($options);
    }

    protected function _setRedirectByItem($item, $id = null)
    {
        $this->_redirectOptions = array('controller' => 'page', 'action' => 'view', 'id' => $item['id_parent']);
    }
}


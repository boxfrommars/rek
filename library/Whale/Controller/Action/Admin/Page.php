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
        Whale_Log::log($idParent);
        if ($idParent) {
            $this->_updateFormByIdParent($idParent);
        }
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
}

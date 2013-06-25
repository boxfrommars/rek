<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_ProductController extends Whale_Controller_Action_Admin_Page
{
    /**
     * @var Catalog_Model_ProductService
     */
    protected $_model;

    /**
     * @var Admin_Form_Product
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_ProductService();
        $this->_form = new Admin_Form_Product();
    }

    public function editAction()
    {
        parent::editAction();
        $productColorService = new Catalog_Model_ProductColorService();
        $this->view->productColors = $productColorService->fetchAll(array('id_product = ?' => $this->_getParam('id')));
    }

    protected function _beforeDelete($id)
    {
        $productColorService = new Catalog_Model_ProductColorService();
        $productColorService->delete(array('id_product = ?' => $id));
    }


    protected function _setRedirectByItem($item, $id = null)
    {
        $id = $item['id'] ?: $id;
        if ($id) {
            $this->_redirectOptions = array('controller' => 'product', 'action' => 'edit', 'id' => $id);
            $this->_redirectRouteName = 'admin';
        }
    }

}


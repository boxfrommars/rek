<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_ProductColorController extends Whale_Controller_Action_Admin_Article
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
        $this->_model = new Catalog_Model_ProductColorService();
        $this->_form = new Admin_Form_ProductColor();
    }

    public function editAction()
    {
        $productId = $this->getParam('id');
        parent::editAction();
        if ($productId) {
            $this->view->productId = $productId;
            $elm = $this->_form->getElement('id_product');
            $elm->setValue($productId);
        }
    }

    public function addAction()
    {
        $productId = $this->getParam('id');
        parent::addAction();
        if ($productId) {
            $this->view->productId = $productId;
            $elm = $this->_form->getElement('id_product');
            $elm->setValue($productId);
        }
    }

    protected function _setRedirectByItem($item)
    {
        $this->_redirectOptions = array('controller' => 'product', 'action' => 'edit', 'id' => $item['id_product']);
    }
}


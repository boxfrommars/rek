<?php

class Catalog_IndexController extends Whale_Controller_Action
{
    /**
     * @var Catalog_Model_CategoryService
     */
    protected $_service;

    public function init()
    {
        parent::init();
        $this->_service = new Catalog_Model_CategoryService();
    }

    public function indexAction()
    {
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('catalog');
        $categoryName = $this->getParam('category');
        if (empty($categoryName)) {
            throw new Zend_Controller_Action_Exception('Не указана категория', 404);
        }
        $category = $this->_service->fetchRow(array('page_url = ?' => $categoryName, 'is_published' => true));
        if (empty($category)) {
            throw new Zend_Controller_Action_Exception('Такой категории не существует', 404);
        }
        $this->view->category = $category;
        $this->view->page = new Whale_Page_SeoItemAdapter($category->toArray());

        $productService = new Catalog_Model_ProductService();

        $products = $productService->fetchAll(array('id_category = ?' => $category['id'], 'is_published = ?' => true));

        $this->view->products = $products;
    }

    public function viewAction()
    {
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('product');

        $categoryName = $this->getParam('category');
        $productName = $this->getParam('product');
        if (empty($categoryName)) {
            throw new Zend_Controller_Action_Exception('Не указана категория', 404);
        }
        $category = $this->_service->fetchRow(array('page_url = ?' => $categoryName, 'is_published' => true));
        if (empty($category)) {
            throw new Zend_Controller_Action_Exception('Такой категории не существует', 404);
        }
        $this->view->category = $category;

        $productService = new Catalog_Model_ProductService();

        $product = $productService->fetch(array('id_category = ?' => $category['id'], 'p.page_url = ?' => $productName,  'is_published = ?' => true));
        $product = $product ?: $productService->fetch(array('id_category = ?' => $category['id'], 'p.id = ?' => $productName,  'is_published = ?' => true));

        $this->view->page = new Whale_Page_SeoItemAdapter($product);

        $this->view->product = $product;
    }
}


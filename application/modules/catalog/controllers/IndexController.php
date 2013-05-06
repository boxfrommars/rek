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
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('catalog');
    }

    public function indexAction()
    {
        $categoryName = $this->getParam('category');
        if (empty($categoryName)) {
            throw new Zend_Controller_Action_Exception('Не указана категория', 404);
        }
        $category = $this->_service->fetchRow(array('page_url = ?' => $categoryName, 'is_published' => true));
        Whale_Log::log($category);
        if (empty($category)) {
            throw new Zend_Controller_Action_Exception('Такой категории не существует', 404);
        }
        $this->view->category = $category;
        $this->view->page = new Whale_Page_SeoItemAdapter($category->toArray());

        $productService = new Catalog_Model_ProductService();

        $products = $productService->fetchAll(array('b.id_parent = ?' => $category['id'], 'is_published = ?' => true));

        $this->view->products = $products;
    }

    public function viewAction()
    {
        $categoryName = $this->getParam('category');
        $productName = $this->getParam('product');
        if (empty($categoryName)) {
            throw new Zend_Controller_Action_Exception('Не указана категория', 404);
        }
        $category = $this->_service->fetchRow(array('page_url = ?' => $categoryName, 'is_published' => true));
        Whale_Log::log($category);
        if (empty($category)) {
            throw new Zend_Controller_Action_Exception('Такой категории не существует', 404);
        }
        $this->view->category = $category;

        $productService = new Catalog_Model_ProductService();

        $product = $productService->fetch(array('b.id_parent = ?' => $category['id'], 'p.page_url = ?' => $productName,  'is_published = ?' => true));

        if (!$product && is_int($productName)) {
            $product = $productService->fetch(array('b.id_parent = ?' => $category['id'], 'p.id = ?' => $productName,  'is_published = ?' => true));
        }

        if (empty($product)) {
            throw new Zend_Controller_Action_Exception('Такого продукта не существует', 404);
        }

        $productColorsService = new Catalog_Model_ProductColorService();

        $productColors = $productColorsService->fetchAll(array('id_product = ?' => $product['id']));
        Whale_Log::log($productColors);

        $this->view->page = new Whale_Page_SeoItemAdapter($product);
        $this->view->productColors = $productColors;

        $this->view->product = $product;
    }
}


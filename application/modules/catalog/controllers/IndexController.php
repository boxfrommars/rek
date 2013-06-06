<?php

class Catalog_IndexController extends Whale_Controller_Action
{
    /**
     * @var Catalog_Model_CategoryService
     */
    protected $_service;

    /**
     * @var int кол-во выводимых последних просмотренных
     */
    protected $_lastViewedCount = 3;

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

        if (empty($category)) {
            throw new Zend_Controller_Action_Exception('Такой категории не существует', 404);
        }
        $productService = new Catalog_Model_ProductService();
        $surfaceService = new Catalog_Model_SurfaceService();
        $countryService = new Catalog_Model_CountryService();
        $brandService = new Catalog_Model_BrandService();
        $colorService = new Catalog_Model_ColorService();

        $this->view->colors = $colorService->fetchAll();
        $this->view->surfaces = $surfaceService->fetchAll();
        $this->view->countries = $countryService->fetchAll();
        $this->view->brands = $brandService->fetchAll();
        $this->view->sizes = $productService->getAdapter()->select()->from(array('p' => 'product'), array('width', 'height'))->group(array('height', 'width'))->query()->fetchAll();
        $this->view->costsRange = $productService->getAdapter()->select()->from(array('p' => 'product'), array('max' => 'max(cost)', 'min' => 'min(cost)'))->query()->fetch();


        $this->view->category = $category;
        $this->view->page = new Whale_Page_SeoItemAdapter($category->toArray());


        $products = $productService->fetchAll(array('b.id_parent = ?' => $category['id'], 'is_published = ?' => true));

        $this->view->products = $products;
    }

    public function viewAction()
    {

        Zend_Session::start();
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

        $product = $productService->fetch(array('b.id_parent = ?' => $category['id'], 'p.page_url = ?' => $productName,  'is_published = ?' => true));

        if (!$product && is_int($productName)) {
            $product = $productService->fetch(array('b.id_parent = ?' => $category['id'], 'p.id = ?' => $productName,  'is_published = ?' => true));
        }

        if (empty($product)) {
            throw new Zend_Controller_Action_Exception('Такого продукта не существует', 404);
        }

        $lastViewedSessionNamespace = new Zend_Session_Namespace('last_viewed_products');

        $recommendedItems = array();
        if (empty($lastViewedSessionNamespace->items)) {
            $lastViewedItemIds = array();
            $lastViewedItems = array();

            $recommendedItems = $productService->fetchAll(array(
                    'is_action = ?' => true,
                    'is_published = ?' => true
                ), null, 5
            );
        } else {
            $lastViewedItemIds = $lastViewedSessionNamespace->items;
            $lastViewedItemIds = array_filter($lastViewedItemIds, function($elm) use ($product) { return $elm != $product['id']; });
            if (!empty($lastViewedItemIds)) {
                $lastViewedItems = $productService->fetchAll(array(
                        'p.id IN (?)' => $lastViewedItemIds,
                        'is_published = ?' => true)
                );
            } else {
                $lastViewedItems = array();
                $recommendedItems = $productService->fetchAll(array(
                        'is_action = ?' => true,
                        'is_published = ?' => true
                    ), null, 5
                );
            }
        }
        $this->view->lastViewed = $lastViewedItems;
        $this->view->recommended = $recommendedItems;
        array_unshift($lastViewedItemIds, $product['id']);
        $lastViewedItemIds = array_slice($lastViewedItemIds, 0, $this->_lastViewedCount);
        $lastViewedSessionNamespace->items = $lastViewedItemIds;

        $productColorsService = new Catalog_Model_ProductColorService();

        $productColors = $productColorsService->fetchAll(array('id_product = ?' => $product['id']));

        $this->view->page = new Whale_Page_SeoItemAdapter($product);
        $this->view->productColors = $productColors;

        $this->view->product = $product;
    }
}


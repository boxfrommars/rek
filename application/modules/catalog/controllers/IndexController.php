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
        $patternService = new Catalog_Model_PatternService();
        $colorService = new Catalog_Model_ColorService();
        $productColorService = new Catalog_Model_ProductColorService();


        $this->view->category = $category;
        $this->view->page = new Whale_Page_SeoItemAdapter($category->toArray());


        $products = $productService->fetchAll(array('b.id_parent = ?' => $category['id'], 'is_published = ?' => true));

        $productIds = array();
        $colorIds = array();
        $surfaceIds = array();
        $countryIds = array();
        $brandIds = array();
        $patternIds = array();
        $sizes = array();
        $minDepth = null;
        $maxDepth = null;
        $minCost = null;
        $maxCost = null;

        foreach ($products as $product) {
            if (!in_array($product['id_surface'], $surfaceIds) && $product['id_surface']) $surfaceIds[] = $product['id_surface'];
            if (!in_array($product['id_country'], $countryIds) && $product['id_country']) $countryIds[] = $product['id_country'];
            if (!in_array($product['id_brand'], $brandIds) && $product['id_brand']) $brandIds[] = $product['id_brand'];
            if (!in_array($product['id_pattern'], $patternIds) && $product['id_pattern']) $patternIds[] = $product['id_pattern'];
            if (!in_array($product['id'], $productIds)) $productIds[] = $product['id'];

            $addSize = true;
            foreach ($sizes as $size) {
                if ($size['width'] == $product['width'] && $size['height'] == $product['height']) $addSize = false;
            }
            if ($addSize) $sizes[] = array('width' => $product['width'], 'height' => $product['height']);

            if ($product['cost'] > $maxCost || $maxCost === null) $maxCost = $product['cost'];
            if ($product['cost'] < $minCost || $minCost === null) $minCost = $product['cost'];

            if ($product['depth'] > $maxDepth || $maxDepth === null) $maxDepth = $product['depth'];
            if ($product['depth'] < $minDepth || $minDepth === null) $minDepth = $product['depth'];
        }

        $productColors = empty($productIds) ? array() : $productColorService->fetchAll(array('id_product IN (?)' => $productIds));
        foreach ($productColors as $productColor) {
            if (!in_array($productColor['id_color'], $colorIds)) $colorIds[] = $productColor['id_color'];
        }

        $this->view->surfaces = empty($surfaceIds) ? array() : $surfaceService->fetchAll(array('id IN (?)' => $surfaceIds));
        $this->view->countries = empty($countryIds) ? array() : $countryService->fetchAll(array('id IN (?)' => $countryIds));
        $this->view->brands = empty($brandIds) ? array() : $brandService->fetchAll(array('id IN (?)' => $brandIds), 'order');
        $this->view->patterns = empty($patternIds) ? array() : $patternService->fetchAll(array('id IN (?)' => $patternIds));
        $this->view->colors = empty($colorIds) ? array() : $colorService->fetchAll(array('id IN (?)' => $colorIds));
        $this->view->sizes = $sizes;
        $this->view->costsRange = array('max' => $maxCost, 'min' => $minCost);
        $this->view->depthRange = array('max' => $maxDepth, 'min' => $minDepth);

        $this->view->products = $products;
    }

    public function brandAction()
    {
        $productService = new Catalog_Model_ProductService();
        $page = $this->_getParam('page');
        $this->_setPage('');
        $products = $productService->fetchAll(array('b.id = ?' => $page['id'], 'is_published = ?' => true));
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

        $pageService = new Page_Model_Service();
        $select = $pageService->getBaseSelect();
        $nextSelect = $pageService->getAdapter()->select()->from(array('s' => $select), '*')->where('entity = ?', 'brand')->where('is_published');
        $this->view->brands = $nextSelect->query()->fetchAll();



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


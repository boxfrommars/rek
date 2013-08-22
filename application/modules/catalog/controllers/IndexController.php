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
        $url = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
        $session = new Zend_Session_Namespace('lastCatalogPage');
        $session->url = $url;
    }

    public function indexAction()
    {
        $this->_setPage('');
        $categoryName = $this->getParam('category');
        if (empty($categoryName)) {
            throw new Zend_Controller_Action_Exception('Не указана категория', 404);
        }
        $category = $this->_service->fetchRow(array('page_url = ?' => $categoryName, 'is_published' => true));

        if (empty($category)) {
            throw new Zend_Controller_Action_Exception('Такой категории не существует', 404);
        }

        $this->view->category = $category;

        $this->_setSearchbar(array('b.id_parent = ?' => $category['id'], 'is_published = ?' => true));

        $productService = new Catalog_Model_ProductService();
        $products = $productService->fetchAllColored(array('b.id_parent = ?' => $category['id'], 'is_published = ?' => true), 'title ASC');

        $this->view->products = $products;
        $this->view->checked = array();
    }

    protected function _setSearchbar($where){

        $productService = new Catalog_Model_ProductService();
        $surfaceService = new Catalog_Model_SurfaceService();
        $countryService = new Catalog_Model_CountryService();
        $brandService = new Catalog_Model_BrandService();
        $patternService = new Catalog_Model_PatternService();
        $colorService = new Catalog_Model_ColorService();
        $productColorService = new Catalog_Model_ProductColorService();

        $products = $productService->fetchAllColored($where, 'title ASC');

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
            if (!in_array($product['id_surface'], $surfaceIds) && $product['id_surface']) $surfaceIds[] = $product['id_surface'];
            if (!in_array($product['color_id_surface'], $surfaceIds) && $product['color_id_surface']) $surfaceIds[] = $product['color_id_surface'];


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

            if (!empty($product['color_cost'])) {
                if ($product['color_cost'] > $maxCost || $maxCost === null) $maxCost = $product['color_cost'];
                if ($product['color_cost'] < $minCost || $minCost === null) $minCost = $product['color_cost'];
            }

            if ($product['depth'] > $maxDepth || $maxDepth === null) $maxDepth = $product['depth'];
            if ($product['depth'] < $minDepth || $minDepth === null) $minDepth = $product['depth'];
        }

        $productColors = empty($productIds) ? array() : $productColorService->fetchAll(array('id_product IN (?)' => $productIds));
        foreach ($productColors as $productColor) {
            if (!in_array($productColor['id_color'], $colorIds)) $colorIds[] = $productColor['id_color'];
        }
        $this->view->surfaces = empty($surfaceIds) ? array() : $surfaceService->fetchAll(array('id IN (?)' => $surfaceIds), 'title ASC');
        $this->view->countries = empty($countryIds) ? array() : $countryService->fetchAll(array('id IN (?)' => $countryIds), 'title ASC');
        $this->view->brands = empty($brandIds) ? array() : $brandService->fetchAll(array(array('key' => 'b.id IN (?)', 'value' => $brandIds)), 'order ASC');
        $this->view->patterns = empty($patternIds) ? array() : $patternService->fetchAll(array('id IN (?)' => $patternIds), 'title ASC');
        $this->view->colors = empty($colorIds) ? array() : $colorService->fetchAll(array('id IN (?)' => $colorIds), 'title ASC');
        $this->view->sizes = $sizes;
        $this->view->costsRange = array('max' => $maxCost, 'min' => $minCost);
        $this->view->depthRange = array('max' => $maxDepth, 'min' => $minDepth);
    }

    public function rekAction()
    {
        $this->_setPage('');
        $page = $this->_getParam('page');
        $rekService = new Catalog_Model_RekService();

        $rek = $rekService->fetchRow(array('id = ?' => $page['id'], 'is_published' => true));

        if (null === $rek) {
            throw new Zend_Controller_Action_Exception('Не найдено', 404);
        }

        $rek = $rek->toArray();

        $this->view->rek = $rek;

        $params = json_decode($rek['params']);

        $where = array('b.id_parent = ?' => $page['id_parent'], 'is_published = ?' => true);
        $checked = array();
        Whale_Log::log($params);
        foreach ($params as $param) {
            if (!empty($param->value)) {
                switch ($param->name) {
                    case 'cost':
                        $costRange = explode(',', $param->value);
                        if (!empty($costRange[0])) $where['clr.cost >= ? OR (clr.cost IS NULL AND p.cost >= ?)'] = (int) $costRange[0];
                        if (!empty($costRange[1])) $where['clr.cost <= ? OR (clr.cost IS NULL AND p.cost <= ?)'] = (int) $costRange[1];
                        break;
                    case 'id_surface':
                        $where['(clr.id_surface IN (?) OR (clr.id_surface IS NULL AND p.id_surface IN (?)))'] = $param->value;
                        break;
                    default:
                        $where['p.' . $param->name . ' = ?'] = $param->value;
                        break;
                }
                $checked[$param->name] = $param->value;
            }
        }

        $productService = new Catalog_Model_ProductService();
        $products = $productService->fetchAllColored($where);

        $this->view->products = $products;
        $this->view->checked = $checked;

        $this->_setSearchbar(array('b.id_parent = ?' => $page['id_parent'], 'is_published = ?' => true));
    }

    public function brandAction()
    {
        $productService = new Catalog_Model_ProductService();
        $page = $this->_getParam('page');
        $this->_setPage('');
        $this->view->brand = $this->view->page->getRaw();


        $products = $productService->fetchAllColored(array('b.id = ?' => $page['id'], 'is_published = ?' => true));
        $this->view->products = $products;

        $this->view->checked = array('id_brand' => $page['id']);
        $this->_setSearchbar(array('b.id_parent = ?' => $page['id_parent'], 'is_published = ?' => true));
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


        $brandProducts = $productService->fetchAll(array(
                'p.id_parent = ?' => $product['id_parent'],
            ), null, 5
        );
        $prodCollections = array();
        foreach ($brandProducts as $prod) {
            if (!empty($prod['id_collection']) && !in_array($prod['id_collection'], $prodCollections)) $prodCollections[] = $prod['id_collection'];
        }
        $rekService = new Catalog_Model_RekService();

        $collections = $rekService->getByParam(array(
            array(
                'name' => 'id_collection',
                'value' => $prodCollections
            ),
        ));

        $collectionIds = array();
        foreach ($collections as $collection) {
            $collectionIds[] = $collection['id'];
        }


        $pageService = new Page_Model_Service();

        $select = $pageService->getBaseSelect();
        $nextSelect = $pageService->getAdapter()->select()->from(array('s' => $select), '*')->where('id IN(?)', $collectionIds)->where('is_published');
        $this->view->collections = $nextSelect->query()->fetchAll();
        Whale_Log::log($this->view->collections);



        $select = $pageService->getBaseSelect();
        $nextSelect = $pageService->getAdapter()->select()->from(array('s' => $select), '*')->where('entity = ?', 'brand')->where('id_parent = ?', $category['id'])->where('is_published');
        $this->view->brands = $nextSelect->query()->fetchAll();



        $this->view->lastViewed = $lastViewedItems;
        $this->view->recommended = $recommendedItems;
        array_unshift($lastViewedItemIds, $product['id']);
        $lastViewedItemIds = array_slice($lastViewedItemIds, 0, $this->_lastViewedCount);
        $lastViewedSessionNamespace->items = $lastViewedItemIds;

        $productColorsService = new Catalog_Model_ProductColorService();
        $productIntersService = new Catalog_Model_ProductInterService();
        $productDecorsService = new Catalog_Model_ProductDecorService();

        $this->view->productColors = $productColorsService->fetchAll(array('id_product = ?' => $product['id']));
        $this->view->productDecors = $productDecorsService->fetchAll(array('id_product = ?' => $product['id']));
        $this->view->productInters = $productIntersService->fetchAll(array('id_product = ?' => $product['id']));

        $this->view->page = new Whale_Page_SeoItemAdapter($product);

        $this->view->product = $product;
    }
}


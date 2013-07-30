<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_RekController extends Whale_Controller_Action_Admin_Page
{
    protected $_redirectOptions = array('action' => 'index', 'controller' => 'category');
    /**
     * @var Catalog_Model_BrandService
     */
    protected $_model;

    /**
     * @var Admin_Form_CatalogItem
     */
    protected $_form;

    public function init()
    {
        parent::init();
        $this->_model = new Catalog_Model_RekService();
        $this->_form = new Admin_Form_Rek();
    }

    public function editAction() {
        parent::editAction();

        $where = array();
        $idParent = $this->getParam('idParent');
        if ($idParent) {
            $where = array('b.id_parent = ?' => $idParent, 'is_published = ?' => true);
        }
        $this->_setSearchbar($where);
    }

    public function addAction() {
        parent::addAction();

        $where = array();
        $idParent = $this->getParam('idParent');
        if ($idParent) {
            $where = array('b.id_parent = ?' => $idParent, 'is_published = ?' => true);
        }
        $this->_setSearchbar($where);
    }


    protected function _setSearchbar($where){

        $productService = new Catalog_Model_ProductService();
        $surfaceService = new Catalog_Model_SurfaceService();
        $countryService = new Catalog_Model_CountryService();
        $brandService = new Catalog_Model_BrandService();
        $patternService = new Catalog_Model_PatternService();
        $colorService = new Catalog_Model_ColorService();
        $productColorService = new Catalog_Model_ProductColorService();

        $products = $productService->fetchAll($where, 'title ASC');

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
        $this->view->surfaces = empty($surfaceIds) ? array() : $surfaceService->fetchAll(array('id IN (?)' => $surfaceIds), 'title ASC');
        $this->view->countries = empty($countryIds) ? array() : $countryService->fetchAll(array('id IN (?)' => $countryIds), 'title ASC');
        $this->view->brands = empty($brandIds) ? array() : $brandService->fetchAll(array(array('key' => 'b.id IN (?)', 'value' => $brandIds)), 'order ASC');
        $this->view->patterns = empty($patternIds) ? array() : $patternService->fetchAll(array('id IN (?)' => $patternIds), 'title ASC');
        $this->view->colors = empty($colorIds) ? array() : $colorService->fetchAll(array('id IN (?)' => $colorIds), 'title ASC');
        $this->view->sizes = $sizes;
        $this->view->costsRange = array('max' => $maxCost, 'min' => $minCost);
        $this->view->depthRange = array('max' => $maxDepth, 'min' => $minDepth);

        $this->view->paramsForm = new Admin_Form_RekParam();
    }
}


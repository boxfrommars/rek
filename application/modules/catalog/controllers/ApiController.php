<?php

class Catalog_ApiController extends Whale_Controller_Action
{
    public function init()
    {
        parent::init();

    }

    public function colorsAction(){
        $this->_helper->layout()->disableLayout();

        $this->getResponse()
            ->setHeader('Content-type', 'text/css');

        $colorService = new Catalog_Model_ColorService();
        $this->view->colors = $colorService->fetchAll();

    }

    public function indexAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
        } else {
            $layout = Zend_Layout::getMvcInstance();
            $layout->setLayout('catalog');
        }

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $productService = new Catalog_Model_ProductService();

        $select = $productService->getBaseSelect(array('is_published = ?' => true));
        $select->joinLeft(
            array('clr' => 'product_color'),
            'clr.id_product = p.id',
            array('color_image' => 'image', 'color_image_preview' => 'image_preview', 'color_cost' => 'cost', 'color_id_surface' => 'id_surface')
        );
        if ($this->getParam('color')) {
	        $select->where('clr.id_color IN (?)', explode(',', $this->getParam('color')));
        }
        if ($this->getParam('category')) {
            $select->where('ct.id = ?', $this->getParam('category'));
        }
        if ($this->getParam('size')) {
            $sizeWhere = array();
            $sizes = explode(',', $this->getParam('size'));
            foreach ($sizes as $size) {
                $dimensions = explode('-', $size);

                $sizeWhere[] = '(p.width = ' . $db->quoteInto('?',$dimensions[0]) . ' AND p.height = ' . $db->quoteInto('?',$dimensions[1]) . ')';
            }

            $sizeWhere = implode(' OR ', $sizeWhere);
            $select->where($sizeWhere);
        }

        if ($this->getParam('surface')) {
            $surface = explode(',', $this->getParam('surface'));
            $select->where('(p.id_surface IN (?) OR clr.id_surface IN (?))', $surface);
        }

        if ($this->getParam('pattern')) {
            $surface = explode(',', $this->getParam('pattern'));
            $select->where('id_pattern IN (?)', $surface);
        }

        if ($this->getParam('country')) {
            $country = explode(',', $this->getParam('country'));
            $select->where('id_country IN (?)', $country);
        }

        if ($this->getParam('brand')) {
            $brand = explode(',', $this->getParam('brand'));
            $select->where('b.id IN (?)', $brand);
        }

        if ($this->getParam('mincost')) {
            $select->where('clr.cost >= ? OR (clr.cost IS NULL AND p.cost >= ?)', $this->getParam('mincost'));
        }

        if ($this->getParam('maxcost')) {
            $select->where('clr.cost <= ? OR (clr.cost IS NULL AND p.cost <= ?)', $this->getParam('maxcost'));
        }

        if ($this->getParam('mindepth')) {
            $select->where('(p.depth >= ? OR p.depth IS NULL)', $this->getParam('mindepth'));
        }

        if ($this->getParam('maxdepth')) {
            $select->where('(p.depth <= ? OR p.depth IS NULL)', $this->getParam('maxdepth'));
        }
        Whale_Log::log($select->assemble());
        $this->view->products = $select->query()->fetchAll();
    }
}


<?php

class Catalog_ApiController extends Whale_Controller_Action
{
    public function init()
    {
        parent::init();

    }

    public function indexAction()
    {
        $this->_helper->layout()->disableLayout();

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $productService = new Catalog_Model_ProductService();

        $select = $productService->getBaseSelect(array('is_published = ?' => true));

        if ($this->getParam('color')) {
            $colorSelect = $productService->getAdapter()->select()->from(array('pcl' => 'product_color'), 'id_product')->where('id IN (?)', explode(',', $this->getParam('color')))->group(array('id_product'))->query()->fetchAll(Zend_Db::FETCH_COLUMN);
            Whale_Log::log($colorSelect);

            $select->where('p.id IN (?)', $colorSelect);
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
            $select->where('id_surface IN (?)', $surface);
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
            $select->where('p.cost >= ', $this->getParam('mincost'));
        }

        if ($this->getParam('maxcost')) {
            $select->where('p.cost <= ', $this->getParam('maxcost'));
        }
        Whale_Log::log($select->assemble());
        $this->view->products = $select->query()->fetchAll();
    }
}


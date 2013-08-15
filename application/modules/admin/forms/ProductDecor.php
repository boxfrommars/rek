<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_ProductDecor extends Admin_Form_CatalogItem
{
    protected function _initMiddleElements()
    {
        $productService = new Catalog_Model_ProductService();
        $products = $productService->fetchAll();
        $productSelectOptions = array();

        foreach ($products as $product) {
            $productSelectOptions[$product['id']] = $product['title'];
        }

        $this->addElement('select', 'id_product', array(
            'label' => "Товар",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $productSelectOptions,
        ));

        parent::_initMiddleElements();

        $this->addElement('text', 'cost', array(
            'label' => "Цена",
            'required' => false,
            'filters' => array('StringTrim', 'Null'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));
    }

    protected function _initTopElements()
    {
        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));
    }

    protected function _initBottomElements()
    {

    }
}
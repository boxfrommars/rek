<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_ProductColor extends Admin_Form_CatalogItem
{
    protected function _initMiddleElements()
    {
        $colorService = new Catalog_Model_ColorService();
        $colors = $colorService->fetchAll();
        $colorSelectOptions = array();

        $productService = new Catalog_Model_ProductService();
        $products = $productService->fetchAll();
        $productSelectOptions = array();


        foreach ($products as $product) {
            $productSelectOptions[$product['id']] = $product['title'];
        }

        foreach ($colors as $color) {
            $colorSelectOptions[$color['id']] = $color['title'];
        }

        $this->addElement('select', 'id_color', array(
            'label' => "Цвет",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $colorSelectOptions,
        ));

        $this->addElement('select', 'id_product', array(
            'label' => "Товар",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $productSelectOptions,
        ));
    }

    protected function _initBottomElements()
    {

    }
}
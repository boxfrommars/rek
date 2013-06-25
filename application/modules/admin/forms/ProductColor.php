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
        $surfaceService = new Catalog_Model_SurfaceService();

        $colors = $colorService->fetchAll();
        $surfaces = $surfaceService->fetchAll();
        $colorSelectOptions = array();
        $surfaceSelectOptions = array();

        $productService = new Catalog_Model_ProductService();
        $products = $productService->fetchAll();
        $productSelectOptions = array();

        foreach ($surfaces as $surface) {
            $surfaceSelectOptions[$surface['id']] = $surface['title'];
        }
        foreach ($products as $product) {
            $productSelectOptions[$product['id']] = $product['title'];
        }

        foreach ($colors as $color) {
            $colorSelectOptions[$color['id']] = $color['title'];
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
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('select', 'id_color', array(
            'label' => "Цвет",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $colorSelectOptions,
        ));

        $this->addElement('select', 'id_surface', array(
            'label' => "Поверхность",
            'required' => true,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'multiOptions' => $surfaceSelectOptions,
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
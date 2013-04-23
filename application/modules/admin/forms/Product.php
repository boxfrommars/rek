<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Product extends Admin_Form_CatalogItem
{
    protected $_dimension = 12;

    protected function _initMiddleElements()
    {
        $this->_addClassNames('row-fluid');
        $brandService = new Catalog_Model_BrandService();
        $countryService = new Catalog_Model_CountryService();
        $surfaceService = new Catalog_Model_SurfaceService();

        $brands = $brandService->fetchAll();
        $countries = $countryService->fetchAll();
        $surfaces = $surfaceService->fetchAll();

        $brandSelectOptions = array();
        $countrySelectOptions = array();
        $surfaceSelectOptions = array();

        foreach ($brands as $brand) {
            $brandSelectOptions[$brand['id']] = $brand['title'];
        }

        foreach ($countries as $country) {
            $countrySelectOptions[$country['id']] = $country['title'];
        }

        foreach ($surfaces as $surface) {
            $surfaceSelectOptions[$surface['id']] = $surface['title'];
        }

        $this->addElement('text', 'article', array(
            'label' => "Артикул",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('text', 'cost', array(
            'label' => "Цена",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('select', 'id_country', array(
            'label' => "Страна производитель",
            'required' => true,
            'validators' => array(),
            'dimension' => 12,
            'multiOptions' => $countrySelectOptions,
        ));

        $this->addElement('select', 'id_brand', array(
            'label' => "Бренд",
            'required' => true,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'multiOptions' => $brandSelectOptions,
        ));

        $this->addElement('select', 'id_surface', array(
            'label' => "Поверхность",
            'required' => true,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'multiOptions' => $surfaceSelectOptions,
        ));

        $this->addElement('text', 'width', array(
            'label' => "Ширина",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('text', 'height', array(
            'label' => "Высота",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('text', 'depth', array(
            'label' => "Толщина",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));
        $this->addElement('checkbox', 'is_action', array(
            'label' => "Акция",
        ));
        $this->addElement('checkbox', 'is_new', array(
            'label' => "Новинка",
        ));
        $this->addElement('checkbox', 'is_hit', array(
            'label' => "Хит",
        ));
    }
}
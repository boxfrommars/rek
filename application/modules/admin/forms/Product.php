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
        $countryService = new Catalog_Model_CountryService();
        $surfaceService = new Catalog_Model_SurfaceService();
        $patternService = new Catalog_Model_PatternService();
        $collectionService = new Catalog_Model_CollectionService();

        $countries = $countryService->fetchAll();
        $surfaces = $surfaceService->fetchAll();
        $patterns = $patternService->fetchAll();
        $collections = $collectionService->fetchAll();

        $countrySelectOptions = array(null => 'Без страны');
        $surfaceSelectOptions = array(null => 'Без поверхности');
        $patternSelectOptions = array(null => 'Без рисунка');
        $collectionSelectOptions = array(null => 'Без коллекции');

        foreach ($countries as $country) {
            $countrySelectOptions[$country['id']] = $country['title'];
        }

        foreach ($surfaces as $surface) {
            $surfaceSelectOptions[$surface['id']] = $surface['title'];
        }

        foreach ($patterns as $pattern) {
            $patternSelectOptions[$pattern['id']] = $pattern['title'];
        }

        foreach ($collections as $collection) {
            $collectionSelectOptions[$collection['id']] = $collection['title'];
        }

        parent::_initMiddleElements();

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
            'filters' => array('Null'),
            'dimension' => $this->_dimension,
            'multiOptions' => $countrySelectOptions,
        ));

        $this->addElement('select', 'id_surface', array(
            'label' => "Поверхность",
            'validators' => array(),
            'filters' => array('Null'),
            'dimension' => $this->_dimension,
            'multiOptions' => $surfaceSelectOptions,
        ));

        $this->addElement('select', 'id_pattern', array(
            'label' => "Рисунок",
            'validators' => array(),
            'filters' => array('Null'),
            'dimension' => $this->_dimension,
            'multiOptions' => $patternSelectOptions,
        ));

        $this->addElement('select', 'id_collection', array(
            'label' => "Коллекция",
            'validators' => array(),
            'filters' => array('Null'),
            'dimension' => $this->_dimension,
            'multiOptions' => $collectionSelectOptions,
        ));

        $this->addElement('text', 'width', array(
            'label' => "Ширина",
            'filters' => array('StringTrim', 'Null'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('text', 'height', array(
            'label' => "Высота",
            'filters' => array('StringTrim', 'Null'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('text', 'depth', array(
            'label' => "Толщина",
            'filters' => array('StringTrim', 'Null'),
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

    protected function _initButtons()
    {

        $this->addElement('button', 'submit', array(
            'label' => 'Сохранить',
            'type' => 'submit',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
        ));

        $this->addElement('button', 'reset', array(
            'label' => 'Отмена',
            'type' => 'reset',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_INFO,
        ));

        $this->addElement('button', 'copy', array(
            'label' => 'Копировать',
            'type' => 'submit',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
        ));

        $this->addDisplayGroup(
            array('submit', 'copy', 'reset'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}
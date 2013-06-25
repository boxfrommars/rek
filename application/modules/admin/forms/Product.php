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

        $countries = $countryService->fetchAll();
        $surfaces = $surfaceService->fetchAll();
        $patterns = $patternService->fetchAll();

        $countrySelectOptions = array();
        $surfaceSelectOptions = array();
        $patternSelectOptions = array();

        foreach ($countries as $country) {
            $countrySelectOptions[$country['id']] = $country['title'];
        }

        foreach ($surfaces as $surface) {
            $surfaceSelectOptions[$surface['id']] = $surface['title'];
        }

        foreach ($patterns as $pattern) {
            $patternSelectOptions[$pattern['id']] = $pattern['title'];
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
            'required' => true,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'multiOptions' => $countrySelectOptions,
        ));

        $this->addElement('select', 'id_surface', array(
            'label' => "Поверхность",
            'required' => true,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'multiOptions' => $surfaceSelectOptions,
        ));

        $this->addElement('select', 'id_pattern', array(
            'label' => "Рисунок",
            'required' => true,
            'validators' => array(),
            'dimension' => $this->_dimension,
            'multiOptions' => $patternSelectOptions,
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
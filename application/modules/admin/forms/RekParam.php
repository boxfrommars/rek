<?php

class Admin_Form_RekParam extends Whale_Form_Base {

    public function init()
    {
        $this->setMethod('post');

        $this->_addClassNames('row-fluid');
        $countryService = new Catalog_Model_CountryService();
        $surfaceService = new Catalog_Model_SurfaceService();
        $patternService = new Catalog_Model_PatternService();

        $countries = $countryService->fetchAll();
        $surfaces = $surfaceService->fetchAll();
        $patterns = $patternService->fetchAll();

        $countrySelectOptions = array(null => 'Не учитывать');
        $surfaceSelectOptions = array(null => 'Не учитывать');
        $patternSelectOptions = array(null => 'Не учитывать');

        foreach ($countries as $country) {
            $countrySelectOptions[$country['id']] = $country['title'];
        }

        foreach ($surfaces as $surface) {
            $surfaceSelectOptions[$surface['id']] = $surface['title'];
        }

        foreach ($patterns as $pattern) {
            $patternSelectOptions[$pattern['id']] = $pattern['title'];
        }

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
    }
}
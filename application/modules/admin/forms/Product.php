<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Product extends Admin_Form_Base
{
    public function init()
    {
        $this->setMethod('post');

        $colorService = new Catalog_Model_ColorService();
        $collectionService = new Catalog_Model_CollectionService();
        $countryService = new Catalog_Model_CountryService();
        $surfaceService = new Catalog_Model_SurfaceService();

        $colors = $colorService->fetchAll();
        $collections = $collectionService->fetchAll();
        $countries = $countryService->fetchAll();
        $surfaces = $surfaceService->fetchAll();

        $colorSelectOptions = array();
        $collectionSelectOptions = array();
        $countrySelectOptions = array();
        $surfaceSelectOptions = array();

        foreach ($colors as $color) {
            $colorSelectOptions[$color['id']] = $color['title'];
        }

        foreach ($collections as $collection) {
            $collectionSelectOptions[$collection['id']] = $collection['title'];
        }

        foreach ($countries as $country) {
            $countrySelectOptions[$country['id']] = $country['title'];
        }

        foreach ($surfaces as $surface) {
            $surfaceSelectOptions[$surface['id']] = $surface['title'];
        }

        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));

        $this->addElement('text', 'title', array(
            'label' => "Заголовок",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('text', 'name', array(
            'label' => "Метка",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('text', 'article', array(
            'label' => "Артикул",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('text', 'cost', array(
                'label' => "Цена",
                'required' => true,
                'filters' => array('StringTrim'),
                'validators' => array(),
                'dimension' => 6
            ));

        $this->addElement('text', 'image', array(
                'label' => "Изображение",
                'required' => true,
                'filters' => array('StringTrim'),
                'validators' => array(),
                'class' => 'hidden image-upload',
                'dimension' => 6,
            ));

        $this->addElement('select', 'id_color', array(
            'label' => "Цвет",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $colorSelectOptions,
        ));

        $this->addElement('select', 'id_country', array(
            'label' => "Страна производитель",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $countrySelectOptions,
        ));

        $this->addElement('select', 'id_collection', array(
            'label' => "Коллекция",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $collectionSelectOptions,
        ));

        $this->addElement('select', 'id_surface', array(
            'label' => "Поверхность",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $surfaceSelectOptions,
        ));

        $this->addElement('text', 'width', array(
            'label' => "Ширина",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('text', 'height', array(
            'label' => "Высота",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('text', 'depth', array(
            'label' => "Глубина",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
        ));

        $this->addElement('textarea', 'description', array(
            'label' => 'Описание',
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'rows' => 4,
        ));

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

        $this->addDisplayGroup(
            array('submit', 'reset'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );

    }
}
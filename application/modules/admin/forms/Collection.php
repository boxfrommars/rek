<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Collection extends Admin_Form_Base
{
    public function init()
    {
        $this->setMethod('post');

        $brandService = new Catalog_Model_BrandService();
        $brands = $brandService->fetchAll();

        $brandSelectOptions = array();
        foreach ($brands as $brand) {
            $brandSelectOptions[$brand['id']] = $brand['title'];
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

        $this->addElement('select', 'id_brand', array(
            'label' => "Бренд",
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'multiOptions' => $brandSelectOptions,
        ));

        $this->addElement('textarea', 'description', array(
            'label' => 'Описание',
            'required' => false,
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
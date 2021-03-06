<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_CatalogItem extends Whale_Form_Page
{
    protected function _initMiddleElements()
    {
        parent::_initMiddleElements();

        $this->addElement('text', 'image', array(
            'label' => "Изображение",
            'required' => false,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'class' => 'image-upload',
            'dimension' => $this->_dimension,
        ));

        $this->addElement('text', 'image_preview', array(
            'label' => "Превью",
            'required' => false,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'class' => 'image-upload',
            'dimension' => $this->_dimension,
        ));
    }
}
<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Gallerymain extends Whale_Form_Titled
{
    protected function _initMiddleElements()
    {
        parent::_initMiddleElements();

        $this->addElement('textarea', 'text', array(
            'label' => 'Текст',
            'required' => false,
            'validators' => array(),
            'rows' => 2,
            'dimension' => 6
        ));

        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));

        $this->addElement('text', 'url', array(
            'label' => "Ссылка",
            'required' => false,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));

        $this->addElement('text', 'image', array(
            'label' => "Изображение",
            'required' => false,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'class' => 'hidden image-upload',
            'dimension' => $this->_dimension,
        ));
    }
}
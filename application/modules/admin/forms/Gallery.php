<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Gallery extends Admin_Form_Base
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));

        $this->addElement('text', 'title', array(
            'label' => "Заголовок",
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
<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_PageText_Form extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->addElement('text', 'mark', array(
            'label' => "Метка",
            'required' => true,
            'readonly' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
        ));

        $this->addElement('text', 'group', array(
            'label' => "Группа",
            'required' => true,
            'readonly' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
        ));

        $this->addElement('text', 'position', array(
            'label' => "Положение",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
        ));

        $this->addElement('text', 'title', array(
            'label' => "Название",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
        ));

        $this->addElement('textarea', 'content', array(
            'label' => "Название",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
            'rows' => 8,
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
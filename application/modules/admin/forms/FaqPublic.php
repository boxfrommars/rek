<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_FaqPublic extends Admin_Form_Base
{
    public function init()
    {
        $this->setMethod('post');
        $this->initFields();
        $this->initActions();
    }

    public function initFields()
    {
        $this->addElement('text', 'name', array(
            'label' => "Имя",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
            'class' => 'form-text form-card',
        ));

        $this->addElement('text', 'email', array(
            'label' => "E-mail",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
            'class' => 'form-text form-card',
        ));

        $this->addElement('textarea', 'question', array(
            'label' => 'Вопрос',
            'required' => true,
            'validators' => array(),
            'dimension' => 6,
            'class' => 'form-text form-card',
            'rows' => 10,
        ));
    }

    public function initActions()
    {
        $this->addElement('button', 'submit', array(
            'label' => 'Сохранить',
            'type' => 'submit',
            'class' => 'btn-orange',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
        ));

        $this->addDisplayGroup(
            array('submit'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}
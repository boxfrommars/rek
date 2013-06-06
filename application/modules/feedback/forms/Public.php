<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Feedback_Form_Public extends Twitter_Bootstrap_Form_Horizontal
{
    protected $_dimension = 6;
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
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
            'class' => 'form-text form-card',
        ));

        $this->addElement('text', 'email', array(
            'label' => "E-mail<sup>*</sup>",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array('EmailAddress'),
            'dimension' => 6,
            'class' => 'form-text form-card',
        ));
        $this->getElement('email')->getDecorator('Label')->setOption('escape',false);

        $this->addElement('text', 'phone', array(
            'label' => "Телефон",
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6,
            'class' => 'form-text form-card',
        ));

        $this->addElement('textarea', 'content', array(
            'label' => 'Вопрос/Заявка',
            'validators' => array(),
            'dimension' => 6,
            'class' => 'form-text form-card',
            'rows' => 10,
        ));
    }

    public function initActions()
    {
        $this->addElement('button', 'submit', array(
            'label' => '',
            'type' => 'submit',
            'class' => 'btn-orange',
            'id' => 'contact_button',
            'value' => '',
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
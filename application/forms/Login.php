<?php
class Application_Form_Login extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'username', array(
            'label' => "Логин",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'class' => 'form-text form-card',
            'dimension' => 6
        ));

        $this->addElement('password', 'password', array(
            'label' => "Пароль",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'class' => 'form-text form-card',
            'dimension' => 6
        ));

        $this->addElement('button', 'submit', array(
            'label' => 'Войти',
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
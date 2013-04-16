<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Faq extends Admin_Form_FaqPublic
{
    public function init()
    {
        parent::init();
    }

    public function initFields()
    {
        parent::initFields();

        $this->addElement('textarea', 'answer', array(
            'label' => 'Ответ',
            'required' => true,
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));
    }

    public function initActions()
    {
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
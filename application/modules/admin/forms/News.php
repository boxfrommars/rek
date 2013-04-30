<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_News extends Admin_Form_Base
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'title', array(
            'label' => "Заголовок",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('text', 'published_at', array(
            'label' => "Дата публикации",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'class' => 'datepicker'
        ));

        $this->addElement('checkbox', 'is_published', array(
            'label' => "Опубликована",
        ));

        $this->addElement('textarea', 'preview', array(
            'label' => 'Анонс',
            'required' => true,
            'validators' => array(),
            'dimension' => 6
        ));

        $this->addElement('textarea', 'content', array(
            'label' => 'Текст',
            'required' => true,
            'validators' => array(),
            'dimension' => 6
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
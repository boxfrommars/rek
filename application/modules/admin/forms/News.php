<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_News extends Whale_Form_Page
{
    protected $_parentPage = 'news';

    protected function _initMiddleElements()
    {
        $this->addElement('text', 'published_at', array(
            'label' => "Дата публикации",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'class' => 'datepicker'
        ));
        parent::_initMiddleElements();
        $this->addElement('textarea', 'preview', array(
            'label' => 'Анонс',
            'required' => false,
            'validators' => array(),
            'rows' => 2,
            'dimension' => 6
        ));
    }
}
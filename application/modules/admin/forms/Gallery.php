<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Gallery extends Whale_Form_Titled
{
    protected function _initMiddleElements()
    {
        parent::_initMiddleElements();

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
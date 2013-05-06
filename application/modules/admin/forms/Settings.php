<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Settings extends Whale_Form_Titled
{
    protected function _initMiddleElements()
    {
        parent::_initMiddleElements();

        $this->addElement('text', 'value', array(
            'label' => "Значение",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));
    }
}
<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Settings extends Admin_Form_Titled
{
    protected function _initBottomElements()
    {
        $this->addElement('text', 'value', array(
            'label' => "Значение",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));
    }
}
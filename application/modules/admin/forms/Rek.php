<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Rek extends Whale_Form_Page
{
    protected function _initMiddleElements()
    {
        parent::_initMiddleElements();

        $this->addElement('textarea', 'params', array(
            'label' => "Параметры",
            'required' => false,
            'validators' => array(),
            'dimension' => $this->_dimension,
        ));
    }
}
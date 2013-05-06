<?php
/**
 * @author Dmitry Groza <boxfrommars@gmail.com>
 */

class Whale_Form_Titled extends Whale_Form_Base {
    public function init()
    {
        $this->setMethod('post');
        $this->_initTopElements();
        $this->_initMiddleElements();
        $this->_initBottomElements();
        $this->_initButtons();
    }

    protected function _initTopElements()
    {
    }

    protected function _initMiddleElements()
    {
        $this->addElement('text', 'title', array(
            'label' => "Заголовок",
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(),
            'dimension' => 6
        ));
    }
    protected function _initBottomElements()
    {

    }
}
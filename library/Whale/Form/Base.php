<?php
/**
 * @author Dmitry Groza <boxfrommars@gmail.com>
 */

class Whale_Form_Base extends Twitter_Bootstrap_Form_Horizontal {

    protected $_dimension = 6;

    public function __construct($options = null) {
        parent::__construct($options);
//        $token = new Zend_Form_Element_Hash('csrf_protect');
//        $token->setSalt('dsfvr7rs8vvr7rfvr7fr64666');
//        $this->addElement($token);
    }

    protected function _initButtons()
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
<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Admin_Form_Base extends Twitter_Bootstrap_Form_Horizontal
{
    public function __construct($options = null) {
        parent::__construct($options);
        $token = new Zend_Form_Element_Hash('csrf_protect');
        $token->setSalt('dsfvr7rs8vvr7rfvr7fr64666');
        $this->addElement($token);
    }
}
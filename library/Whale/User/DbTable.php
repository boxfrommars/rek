<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_User_DbTable extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';

    public function fetch($id)
    {
        return $this->getAdapter()->select()
            ->from(array('u' => $this->_name), '*')
            ->joinLeft(
                array('r' => 'role'),
                'u.id_role = r.id',
                array('role' => 'name')
            )
            ->where('u.id = ?', $id)
            ->query()->fetchObject();
    }
}
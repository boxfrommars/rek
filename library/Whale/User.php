<?php
/**
 *
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_User
{
    protected $_id;
    protected $_role = null;
    protected $_username;
    protected $_email;
    protected $_firstName;
    protected $_middleName;
    protected $_lastName;

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->_firstName;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }

    public function setMiddleName($middleName)
    {
        $this->_middleName = $middleName;
    }

    public function getMiddleName()
    {
        return $this->_middleName;
    }

    public function setRole($role)
    {
        $this->_role = $role;
    }

    public function getRole()
    {
        return $this->_role;
    }

    public function setUsername($username)
    {
        $this->_username = $username;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function isGuest()
    {
        return $this->_role == 'guest';
    }

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . preg_replace('/(?:^|_)(.?)/e', "strtoupper('$1')", $key);
            if (!method_exists($this, $method)) {
                throw new Zend_Exception('method ' . $method . ' not exists in class ' . __CLASS__);
            } else {
                $this->$method($value);
            }
        }

        return $this;
    }

    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'role' => $this->getRole(),
            'firstName' => $this->getFirstName(),
            'middleName' => $this->getMiddleName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
        );
    }
}
<?php
class Whale_API_Response {

    /**
     * @var integer|null
     */
    protected $_id = null;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     * @var array
     */
    protected $_error = array(
        'code' => null,
        'message' => null,
    );

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        return $this->_params[$name];
    }

    public function getData()
    {
        $data = array(
            'id' => $this->getId()
        );

        if (empty($this->getError()['code'])) {
            $data['result'] = $this->getParams();
        } else {
            $data['error'] = $this->getError();
        }
        return $data;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->_params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param string $message
     * @param int $code
     * @return $this
     */
    public function setError($message, $code = 500)
    {
        $this->_error = array(
            'message' => $message,
            'code' => $code,
        );
        return $this;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->_error;
    }
}
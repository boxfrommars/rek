<?php
/**
 * @copyright  (c) 2013
 * @author Franky Calypso <franky.calypso@gmail.com>
 */
class Whale_Db_TableCached extends Whale_Db_Table
{
    public function __call($name, $arguments)
    {
        // если метод для кеша:
        if (preg_match('/^cached_(.+)$/', $name, $methodName) && method_exists($this, $methodName[1])) {

            $cache = Whale_Get::cache('default');
            $className = get_class($this);

            $key = md5('model_' . $className . '_' . $methodName[1] . '_' . print_r($arguments, true));

            if (!$result = $cache->load($key)) {
                $result = call_user_func_array(array($this, $methodName[1]), $arguments);
                $cache->save($result, $key, array('model', $className, $methodName[1]));
                Whale_Log::log($methodName[1] . ' not from cache');
            } else {
                Whale_Log::log($methodName[1] . ' from cache');
            }

            return $result;
        } else {
            throw new Exception('Call to undefined method ' . $name);
        }
    }
}
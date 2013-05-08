<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xu
 * Date: 07.05.13
 * Time: 13:06
 * To change this template use File | Settings | File Templates.
 */

interface Default_Model_SearchDocInterface {
    public function getTitle();
    public function getContent();
    public function getType();
    public function getUrl();
    public function getId();
    public function getDescription();
    public function getKeywords();
}
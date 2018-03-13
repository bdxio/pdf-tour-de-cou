<?php

/**
 * Created by IntelliJ IDEA.
 * User: remigoyard
 * Date: 03/10/2015
 * Time: 20:07
 */
class Model_Log
{

    public static function debug($message){
        /** @var Zend_Log $logger */
        $logger = Zend_Registry::get('Zend_Log');
        $logger->log($message, Zend_Log::DEBUG);
    }

    public static function log($message, $priority = Zend_Log::INFO){
        /** @var Zend_Log $logger */
        $logger = Zend_Registry::get('Zend_Log');
        $logger->log($message, $priority);
    }
}
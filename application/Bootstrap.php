<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Zend auto loader initialisation
     */
    protected function _initAutoloader() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH
        ));
        return $autoloader;
    }

    protected function _initLogger() {
        $this->bootstrap("log");
        $logger = $this->getResource("log");
        Zend_Registry::set("Zend_Log", $logger);
    }


}


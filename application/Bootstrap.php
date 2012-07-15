<?php
/**
 * Bootstrap
 * @package Bootstrap
 */

// Initialize error level
error_reporting(E_ALL);

// Register the library
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Pheanstalk_');

/**
 * Bootstrap class
 * @author matthieu
 * @package Bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Application's configuration
     */
    protected function _initConfiguration()
    {
        $configuration = new Zend_Config($this->getOptions());
        // Configuration is available in the registry
        Zend_Registry::set('configuration', $configuration);
    }

    /**
     * UTF-8 and locale
     */
    protected function _initUTF8()
    {
        header('Content-Type: text/html; charset=utf-8');
        // Set encoding for the extension mb_string
        mb_internal_encoding('UTF-8');
        // Set locale
        setlocale(LC_ALL, 'en_US.UTF8');
    }

}

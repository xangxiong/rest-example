<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ServiceProviderInterface, AutoloaderProviderInterface, ConfigProviderInterface {
	protected $eventManager;
	
    public function onBootstrap($e) {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * Implement to provide the confirmation for this module
     * 
     * @return Array
     * */
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Implement to provide the configuration for the autoloader so that it can load the Controller Classes
     * 
     * @return Array
     * */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	public function getServiceConfig() {
		return array(
			
		);
	}
}
?>
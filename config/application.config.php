<?php
// NOTE: not the best place to put the production, development, staging definition, but I prefer to have it here
//       instead in the VirtualHost SetEnv APPLICATION_ENV 'production'
$env = getenv('APPLICATION_ENV');
$env = (empty($env)) ? 'development' : $env;

$enable_caching = in_array($env, array('production', 'staging'));

return array(
    'modules' => array(
        'Application',
    ),
	
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
		
        'config_glob_paths' => array(
            sprintf('config/autoload/{,*.}{global,%s,local}.php', $env),
        ),
		
		'config_cache_enabled' => $enable_caching,
		'config_cache_key' => 'config',
		'module_map_cache_enabled' => $enable_caching,
		'module_map_cache_key' => 'module',
		'cache_dir' => 'config/cache',
		'check_dependencies' => $enable_caching,
    ),
	
	// Initial configuration with which to seed the ServiceManager.
	// Should be compatible with Zend\ServiceManager\Config.
	'service_manager' => array(
        'factories' => array(
            // main database adapter
			'db' => function($serviceManager) {
				$config = $serviceManager->get('Config');
				return new \Zend\Db\Adapter\Adapter($config['db']);
			},
        ),
    ),
);
?>
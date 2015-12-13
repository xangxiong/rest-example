<?php
return array(
    'router' => array(
        'routes' => array(
			'rest-users' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '/rest/users/:user_id/:controller[/:id]',
					'constraints' => array(
						'controller' => '[a-zA-Z][a-zA-Z0-9_\-]*',
						'user_id' => '[0-9]+',
						'id' => '[0-9]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller\Rest\Users',
						'action' => false,
					),
				),
			),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            // http://framework.zend.com/manual/2.2/en/modules/zend.mvc.services.html#zend-cache-service-storagecacheabstractservicefactory
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            // http://framework.zend.com/manual/2.2/en/modules/zend.mvc.services.html#zend-log-loggerabstractservicefactory
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
		'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'view_manager' => array(
		'strategies' => array(
			'ViewJsonStrategy',
		),
		'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',			
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
			'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Rest\Users\Shifts' => 'Application\Controller\Rest\Users\ShiftsController',
			'Application\Controller\Rest\Users\ShiftMembers' => 'Application\Controller\Rest\Users\ShiftMembersController',
			'Application\Controller\Rest\Users\ShiftManagers' => 'Application\Controller\Rest\Users\ShiftManagersController',
			'Application\Controller\Rest\Users\WeeklyHours' => 'Application\Controller\Rest\Users\WeeklyHoursController',
        ),
    ),
);
?>
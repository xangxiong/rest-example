<?php
namespace Application\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\Hydrator\DateTimeRFC2822Strategy;

class Users extends TableGateway {
	public function __construct(Adapter $db) {		
		$resultSet = new HydratingResultSet();
		$hydrator = new ArraySerializable();
		
		$rfc2822 = new DateTimeRFC2822Strategy();
		$hydrator->addStrategy('created_at', $rfc2822);
		$hydrator->addStrategy('updated_at', $rfc2822);
		
		$resultSet->setHydrator($hydrator);
		parent::__construct('users', $db, null, $resultSet);
	}
	
	public static function factory(ServiceLocatorInterface $service_manager) {
		return new self($service_manager->get('db'));
	}
}
?>
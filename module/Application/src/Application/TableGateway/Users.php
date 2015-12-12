<?php
namespace Application\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\Hydrator\DateTimeRFC2822Strategy;

class Users extends Base {
	public function __construct(Adapter $db) {
		$rfc2822 = new DateTimeRFC2822Strategy();
		$strategies = array(
			'created_at' => $rfc2822,
			'updated_at' => $rfc2822
		);
		
		parent::__construct('users', $db, $strategies);
	}
	
	public static function factory(ServiceLocatorInterface $service_manager) {
		return new self($service_manager->get('db'));
	}
}
?>
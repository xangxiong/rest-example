<?php
namespace Application\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\Hydrator\DateTimeRFC2822Strategy;

class Shifts extends Base {
	public function __construct(Adapter $db) {
		$rfc2822 = new DateTimeRFC2822Strategy();
		$strategies = array(
			'start_time' => $rfc2822,
			'end_time' => $rfc2822,
			'created_at' => $rfc2822,
			'updated_at' => $rfc2822
		);
		
		parent::__construct('shifts', $db, $strategies);
	}
	
	public static function factory(ServiceLocatorInterface $service_manager) {
		return new self($service_manager->get('db'));
	}
}
?>
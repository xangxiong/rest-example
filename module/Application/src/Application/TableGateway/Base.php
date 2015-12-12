<?php
namespace Application\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;

use Application\Hydrator\DateTimeRFC2822Strategy;

class Base extends TableGateway {
	public function __construct($table, Adapter $db, $strategies = array()) {		
		$hydrator = new ArraySerializable();
		
		foreach($strategies as $field => $strategy) {
			$hydrator->addStrategy($field, $strategy);
		};
		
		$resultSet = new HydratingResultSet();
		$resultSet->setHydrator($hydrator);
		
		parent::__construct($table, $db, null, $resultSet);
	}
}

?>
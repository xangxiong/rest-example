<?php
namespace Application\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class Users extends TableGateway {
	public function __construct(Adapter $db) {
		parent::__construct('users', $db);
	}
}
?>
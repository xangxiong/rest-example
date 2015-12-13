<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Users;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;

/**
* Implementation for: As an employee, I want to be able to contact my managers, by seeing manager
* 					 contact information for my shifts.
* */
class ShiftManagersController extends AbstractRestfulController {
	/**
	 * Get the list of managers for my shifts
	 * 
	 * @param $id Employee ID (Manager)
	 * */
	public function getList($id = false) {
		$users_gateway = Users::factory($this->getServiceLocator());
		
		$employee_id = $this->params()->fromRoute('user_id');
		$table = $users_gateway->getTable();
		
		// select all managers who manages my shifts
		$result = $users_gateway->select(function($select) use($employee_id, $id, $table) {
			// the shifts they manages
			$select->join(array('S' => 'shifts'),
						  "{$table}.id = S.manager_id",
						  array(),
						  Select::JOIN_INNER);
			
			$where = new Where();
			// we only want shifts I work on
			$where->equalTo("S.employee_id", $employee_id);
			// ensures that the user is a manager
			$where->equalTo("{$table}.role", 'manager');
			
			if($id !== false) {
				$where->equalTo("{$table}.id", $id);
			}
			
			$select->where($where);
		});
		
		return $result;
	}
	
	/**
	 * Get the single manager for the given Employee ID
	 * 
	 * @param $id Employee ID (Manager)
	 * */
	public function get($id) {
		$result = $this->getList($id);
		if($result->count() > 0) {
			return $result->current();
		} else {
			return $this->responseError(404, "invalid id");
		}
	}
}
?>
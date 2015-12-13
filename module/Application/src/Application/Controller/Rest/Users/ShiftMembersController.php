<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Users;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;

/**
* Impementation for: As an employee, I want to know who I am working with, by being able to see the employees
* 					  that are working during the same time period as me.
* */
class ShiftMembersController extends AbstractRestfulController {
	/**
	 * Get the list of employees working with the given Employee ID
	 * 
	 * @param $id Employee ID
	 * */
	public function getList($id = false) {
		$users_gateway = Users::factory($this->getServiceLocator());
		
		$employee_id = $this->params()->fromRoute('user_id');
		$table = $users_gateway->getTable();
		
		// select all users who works during the same time period
		$result = $users_gateway->select(function($select) use($employee_id, $id, $table) {
			// the shifts other employees work
			$select->join(array('S' => 'shifts'),
						  "{$table}.id = S.employee_id",
						  array(),
						  Select::JOIN_INNER);
			// the shifts the current employee work
			$select->join(array('S2' => 'shifts'),
						  new Expression("(S.start_time >= S2.start_time
											AND S.start_time < S2.end_time)
										OR (S.end_time >= S2.start_time
											AND S.end_time < S2.end_time)"),
						  array(),
						  Select::JOIN_INNER);
			
			$where = new Where();
			// we do not want to know about our own shifts
			$where->notEqualTo("S.employee_id", $employee_id);
			// we want to use only our shifts as a comparison
			$where->equalTo('S2.employee_id', $employee_id);
			
			if($id !== false) {
				$where->equalTo("{$table}.id", $id);
			}
			
			$select->where($where);
		});
		
		return $result;
	}
	
	/**
	 * Get the single employee for the given Employee ID
	 * 
	 * @param $id Employee ID
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
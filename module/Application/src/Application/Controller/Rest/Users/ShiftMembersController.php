<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Shifts;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;

class ShiftMembersController extends AbstractRestfulController {
	protected function select($id = false) {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		
		$employee_id = $this->params()->fromRoute('user_id');
		$table = $shifts_gateway->getTable();
		
		// select all users who works during the same time period
		$result = $shifts_gateway->select(function($select) use($employee_id, $table) {
			// the user information for the current shift
			$select->join(array('U' => 'users'),
						  "{$table}.employee_id = U.id",
						  array(
								'name',
								'email',
								'phone'
						  ),
						  Select::JOIN_INNER);	
			// other people's shifts that overlaps with the current shift
			$select->join(array('S' => 'shifts'),
						  new Expression("({$table}.start_time >= S.start_time
											AND {$table}.start_time < S.end_time)
										OR ({$table}.end_time >= S.start_time
											AND {$table}.end_time < S.end_time)"),
						  array(),
						  Select::JOIN_INNER);
					
			
			$where = new Where();
			// we do not want to know about our own shifts
			$where->notEqualTo("{$table}.employee_id", $employee_id);
			// we want to use only our shifts as a comparison
			$where->equalTo('S.employee_id', $employee_id);
			
			$select->where($where);
		});
		
		return $result;
	}
	
	/**
	 * Impementation for: As an employee, I want to know who I am working with, by being able to see the employees
	 * 					  that are working during the same time period as me.
	 *
	 * */
	public function getList() {
		return $this->select();
	}
	
	public function get($id) {
		$result = $this->select($id);
		if($result->count() > 0) {
			return $result->current();
		} else {
			return $this->responseError(404, "invalid id");
		}
	}
	
	/*
	public function create($data) {
		
	}
	
	public function update($id, $data) {
		
	}
	
	public function delete($id) {
		
	}
	*/
}
?>
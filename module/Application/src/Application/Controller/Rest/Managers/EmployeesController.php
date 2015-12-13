<?php
namespace Application\Controller\Rest\Managers;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Users;

/**
* Implementation for: As a manager, I want to contact an employee, by seeing employee details.
* */
class EmployeesController extends AbstractRestfulController {
	/**
	 * Get the list of employees
	 * 
	 * @param $id Employee ID (employee)
	 * */
	public function getList($id = false) {
		$users_gateway = Users::factory($this->getServiceLocator());
		
		// NOTE: manager_id is not currently use, but can be use for verification/authorization purposes
		$manager_id = $this->params()->fromRoute('manager_id');
		
		// select all employees
		$result = $users_gateway->select(array(
			'role' => 'employee'
		));
		
		return $result;
	}
	
	/**
	 * Get the single employee for the given employee ID
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
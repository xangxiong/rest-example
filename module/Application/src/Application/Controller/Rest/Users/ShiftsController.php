<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Shifts;

/**
* Impementation for: As an employee, I want to know when I am working, by being able to see
* 					  all of the shifts assigned to me.
* */
class ShiftsController extends AbstractRestfulController {
	/**
	 * Get the list of shifts for the given Employee ID
	 * 
	 * @param $id Shift ID
	 * */
	public function getList($id = false) {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		
		// select all shifts for the given user
		$result = $shifts_gateway->select(array(
			'employee_id' => $this->params()->fromRoute('user_id')
		));
		
		return $result;
	}
	
	/**
	 * Get the single shift for the given Shift ID
	 * 
	 * @param $id Shift ID
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
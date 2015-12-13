<?php
namespace Application\Controller\Rest\Managers;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Shifts;

/**
* Impementation for: As a manager, I want to schedule my employees, by creating shifts for any employee.
* 
* */
class ShiftsController extends AbstractRestfulController {
	/**
	 * Allows a manager to crete a shift for an employee
	 * Curl Execution
	 * 		curl --data "employee_id=1&start_time=Mon, 14 Dec 2015 08:00:00 -0800&end_time=Mon, 14 Dec 2015 17:00:00 -0800" http://localhost:8080/rest/managers/2/shifts/
	 * 
	 * @param Array $data
	 * 			employee_id
	 * 			break
	 * 			start_time
	 * 			end_time
	 **/
	public function create($data) {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		$manager_id = $this->params()->fromRoute('manager_id');
		
		if(empty($data['employee_id'])) {
			return $this->responseError(400, 'empty employee_id');
		}
		
		if(empty($data['start_time'])) {
			return $this->responseError(400, 'empty start time');
		}
		$data['start_time'] = strtotime($data['start_time']);
		if($data['start_time'] === false || $data['start_time'] == 0) {
			return $this->responseError(400, 'invalid start time');
		}
		
		if(empty($data['end_time'])) {
			return $this->responseError(400, 'empty end time');
		}
		$data['end_time'] = strtotime($data['end_time']);
		if($data['end_time'] === false || $data['end_time'] == 0) {
			return $this->responseError(400, 'invalid end time');
		}
		
		$data['break'] = (empty($data['break'])) ? 0 : floatval($data['break']);
		$data['manager_id'] = $manager_id;
		
		$cnt = $shifts_gateway->insert($data);
		if($cnt > 0) {
			return array(
				'shift_id' => $shifts_gateway->getLastInsertValue()
			);
		}
	}
}
?>
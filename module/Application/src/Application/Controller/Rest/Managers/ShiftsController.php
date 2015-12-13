<?php
namespace Application\Controller\Rest\Managers;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Shifts;

use Zend\Db\Sql\Where;

/**
* Implementation for: As a manager, I want to schedule my employees, by creating shifts for any employee.
* Implementation for: As a manager, I want to see the schedule, by listing shifts within a specific time period.
* Implementation for: As a manager, I want to be able to change a shift, by updating the time details.
* Implementation for: As a manager, I want to be able to assign a shift, by changing the employee that will work a shift.
* */
class ShiftsController extends AbstractRestfulController {
	/**
	 * Get the list of shifts for the given Manager ID.  If a start_time and/or end_time is provided, will restrict the list of shifts to only shifts within those datetime
	 * 
	 * @param $id Shift ID
	 * */
	public function getList($id = false) {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		
		$manager_id = $this->params()->fromRoute('manager_id');
		
		// shifts within this start/end time
		$start_time = $this->params()->fromQuery('start_time');		
		$end_time = $this->params()->fromQuery('end_time');
		
		if(!empty($start_time)) {
			$start_time = strtotime($start_time);
			if($start_time === false || $start_time == 0) {
				return $this->responseError(400, 'invalid start time');
			}
			$start_time = date('Y-m-d H:i:s', $start_time);
		}
		
		if(!empty($end_time)) {
			$end_time = strtotime($end_time);
			if($end_time === false || $end_time == 0) {
				return $this->responseError(400, 'invalid end time');
			}
			$end_time = date('Y-m-d H:i:s', $end_time);
		}		
		
		// select all shifts for the given manager
		$result = $shifts_gateway->select(function($select) use ($manager_id, $start_time, $end_time, $shifts_gateway) {
			$where = new Where();
			$where->equalTo('manager_id', $manager_id);
			
			$where2 = $where->nest();
			if(!empty($start_time)) {
				$nest = $where2->or->nest();
				$nest->lessThanOrEqualTo('start_time', $start_time);
				$nest->greaterThan('end_time', $start_time);
			}
			if(!empty($end_time)) {
				$nest = $where2->or->nest();
				$nest->lessThanOrEqualTo('start_time', $end_time);
				$nest->greaterThan('end_time', $end_time);				
			}
			
			$select->where($where);
			//echo $shifts_gateway->getSql()->getSqlStringForSqlObject($select);
		});
		
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
	
	/**
	 * Allows a manager to update a shift
	 * Curl Execution
	 * 		curl -X PUT --data "employee_id=1&start_time=Mon, 14 Dec 2015 08:00:00 -0800&end_time=Mon, 14 Dec 2015 17:00:00 -0800&break=2" http://localhost:8080/rest/managers/2/shifts/6
	 * 
	 * @param Array $data
	 * 			employee_id
	 * 			break
	 * 			start_time
	 * 			end_time
	 **/
	public function update($id, $data) {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		// NOTE: manager_id is not currently use, but can be use for verification/authorization purposes
		$manager_id = $this->params()->fromRoute('manager_id');
		
		if(!empty($data['start_time'])) {
			$data['start_time'] = strtotime($data['start_time']);
			if($data['start_time'] === false || $data['start_time'] == 0) {
				return $this->responseError(400, 'invalid start time');
			}
		}
		
		if(!empty($data['end_time'])) {
			$data['end_time'] = strtotime($data['end_time']);
			if($data['end_time'] === false || $data['end_time'] == 0) {
				return $this->responseError(400, 'invalid end time');
			}
		}
		
		if(isset($data['break'])) {
			$data['break'] = (empty($data['break'])) ? 0 : floatval($data['break']);
		}
		
		$cnt = $shifts_gateway->update($data, array(
			'id' => $id
		));
		
		return array(
			'affected' => $cnt
		);
	}
}
?>
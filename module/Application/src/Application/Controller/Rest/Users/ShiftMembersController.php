<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Shifts;

class ShiftMembersController extends AbstractRestfulController {
	public function getList() {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		
		// select all shifts for the given user
		$result = $shifts_gateway->select(array(
			'employee_id' => $this->params()->fromRoute('user_id')
		));
		
		return $result;
	}
	
	public function get($id) {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		
		// select only the shift with the given id
		$shift = $shifts_gateway->select(array('id' => $id));
		if($shift->count() > 0) {
			return $shift->current();
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
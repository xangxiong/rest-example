<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Users;

class ShiftMembersController extends AbstractRestfulController {
	public function getList() {
		$users_gateway = Users::factory($this->getServiceLocator());
		
		$employee_id = $this->params()->fromRoute('user_id');
		
		// TODO: working here
		// select all users who works during the same time period
		$result = $users_gateway->select(function($select) use($employee_id) {
			$select->join(array(''));
		});
		
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
<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Shifts;

class ShiftsController extends AbstractRestfulController {
	public function getList() {
		$service_manager = $this->getServiceLocator();
		$shifts_gateway = new Shifts($service_manager->get('db'));
		
		$result = $shifts_gateway->select(array(
			'employee_id' => $this->params()->fromRoute('user_id')
		));
		
		return $result;
	}
	
	public function get($id) {
		$mdl = Assets::factory($this->getServiceLocator());
		$item = $mdl->get($id);
		return $item;
	}
	
	public function create($data) {
		
	}
	
	public function update($id, $data) {
		
	}
	
	public function delete($id) {
		
	}
}
?>
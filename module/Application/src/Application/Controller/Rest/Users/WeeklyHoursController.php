<?php
namespace Application\Controller\Rest\Users;

use Application\Mvc\Controller\AbstractRestfulController;
use Application\TableGateway\Shifts;

use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;

/**
 * Impementation for: As an employee, I want to know how much I worked, by being
 *					  able to get a summary of hours worked for each week.
 * */
class WeeklyHoursController extends AbstractRestfulController {
	/**
	 * Get the list of weekly hours for the given Employee ID
	 * 
	 * @param $id Week #
	 * */
	public function getList($id = false) {
		$shifts_gateway = Shifts::factory($this->getServiceLocator());
		
		$employee_id = $this->params()->fromRoute('user_id');
		
		// aggregate hours worked each week by the given user
		$result = $shifts_gateway->select(function($select) use ($employee_id, $id) {
			$select->columns(array(
				'employee_id',
				'week_id' => new Expression("WEEK(start_time)"),
				'start_of_week' => new Expression("DATE_FORMAT(
						DATE_ADD(start_time, INTERVAL(1 - DAYOFWEEK(start_time)) + 1 DAY),
						'%a, %d %b %Y'
					)"),
				'end_of_week' => new Expression("DATE_FORMAT(
						DATE_ADD(start_time, INTERVAL(7 - DAYOFWEEK(start_time)) + 1 DAY),
						'%a, %d %b %Y'
					)"),
				'hours' => new Expression("SUM(TIMESTAMPDIFF(HOUR, start_time, end_time))")
			));
			
			$where = new Where();
			$where->equalTo('employee_id', $employee_id);
			if($id !== false) {
				$where->expression("WEEK(start_time) = ?", array($id));
			}
			
			$select->where($where);
			
			$select->group(array(
				new Expression("WEEK(start_time)")
			));
		});
		
		return $result;
	}
	
	/**
	 * Get the single week hours for the given Week #
	 * 
	 * @param $id Week #
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
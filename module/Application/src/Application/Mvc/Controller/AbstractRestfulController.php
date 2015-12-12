<?php
namespace Application\Mvc\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ZendAbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;
use Zend\Http\PhpEnvironment\Response;
use Zend\Db\ResultSet\AbstractResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AbstractRestfulController extends ZendAbstractRestfulController {	
	/**
	 * Override this function to ensure that all values returned is always a JsonModel
	 * @param MvcEvent $e
	 * @return JsonModel
	 * */
	public function onDispatch(MvcEvent $e) {
		try {			
			$return = parent::onDispatch($e);
		} catch(\Exception $ex) {
			$previous = $ex->getPrevious();
			if($previous) {
				$ex = $previous;
			}
			return $this->responseError(500, $ex->getMessage());
		}
		
		if(is_array($return) || $return instanceof \Iterator || $return instanceof \ArrayObject) {
			// encapsulate arrays, Iterator, or ArrayObject in a JsonModel
			$return = new JsonModel($return);
			$e->setResult($return);
		} else if(!($return instanceof JsonModel) && !($return instanceof Response)) {
			// unrecognized return type, return output as is
			return $this->responseError(500,  'Unknown return format');
		}
		
		return $return;
	}
	
	/**
	 * Alter the existing response with the given code and message
	 * @param int $code
	 * @param string $msg
	 * @return \Zend\Http\PhpEnvironment\Response
	 * */
	protected function responseError($code, $msg = null) {
		$response = $this->getResponse();
		$response->setStatusCode($code);
		if(!empty($msg)) {
			$msg = Json::encode(array(
				'message' => $msg
			));
			
			$response->setContent($msg);
		}
		return $response;
	}
}
?>
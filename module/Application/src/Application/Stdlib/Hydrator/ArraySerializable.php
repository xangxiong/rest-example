<?php
namespace Application\Stdlib\Hydrator;

use Zend\Stdlib\Hydrator\ArraySerializable as ZendArraySerializable;

class ArraySerializable extends ZendArraySerializable {	
	public function getStrategies() {
		return $this->strategies;
	}
}
?>
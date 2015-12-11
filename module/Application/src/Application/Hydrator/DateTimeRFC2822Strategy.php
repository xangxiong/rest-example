<?php
namespace Application\Hydrator;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class DateTimeRFC2822Strategy extends DefaultStrategy {
	/**
	 * Convert a normal date string to RFC2822 date string format
	 *
	 **/
	public function hydrate($value) {
		if(empty($value)) {
			return null;
		}
		
		$value = new \DateTime($value);
		return $value->format(\DateTime::RFC2822);
	}
	
	/**
	 * Converts a RFC2822 date string format to MySQL (Y-m-d H:i:s)
	 *
	 * */
	public function extract($value) {
		if(empty($value)) {
			return null;
		}
		
		$value = new \DateTime($value);
		return $value->format('Y-m-d H:i:s');
	}
}
?>
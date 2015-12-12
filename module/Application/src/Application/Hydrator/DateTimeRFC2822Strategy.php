<?php
namespace Application\Hydrator;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class DateTimeRFC2822Strategy extends DefaultStrategy {
	/**
	 * Converts a MySQL (Y-m-d H:i:s) date string to RFC2822 date string format
	 *
	 * */
	public function extract($value) {
		if(empty($value)) {
			return null;
		}
		
		$value = new \DateTime($value);
		return $value->format(\DateTime::RFC2822);
	}
}
?>
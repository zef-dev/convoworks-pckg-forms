<?php

namespace Convo\Pckg\Forms;

class FormValidationResult
{

    private $_errors    =   [];
	
	public function addError( $field, $msg) {
	    $this->_errors[]   =   ['field' => $field, 'message' => $msg];
	}
	
	public function isValid() {
	    return empty( $this->_errors);
	}
	
	public function getErrors() {
	    return $this->_errors;
	}
	
	public function getMessage() {
	    $msg   =   [];
	    foreach ( $this->_errors as $error) {
	        $msg[] = $error['message'].' ['.$error['field'].']';
	    }
	    return implode( ', ', $msg);
	}

	// UTIL
	public function __toString()
	{
	    return get_class( $this).'['.count( $this->_errors).']';
	}
}
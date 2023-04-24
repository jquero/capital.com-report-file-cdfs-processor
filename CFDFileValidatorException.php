<?php

class CFDFileValidatorException extends \Exception {
	protected $errors = [];

	public function addError( $msg ){
		$this->errors[] = $msg;
	}

	public function setErrors( $errors ){
		$this->errors = $errors;
	}

	public function getErrors(){
		return $this->errors;
	}
}
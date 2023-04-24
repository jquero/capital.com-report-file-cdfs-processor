<?php

class CFDFileValidator {

	protected $pathFile;
	protected $fd;
	protected $errors = [];

	public function __construct( $file ){
		$this->pathFile = $file;
	}

	public function validate(){
		$this->fd = fopen( $this->pathFile, 'r' );
		
		$this->checkHeaders();

		fclose( $this->fd );

		if( !empty( $this->errors ) ){
			$e = new CFDFileValidatorException( "Error al validar el fichero: " . $this->pathFile );
			$e->setErrors( $this->errors );
			throw $e;
		}
	}

	protected function checkHeaders(){
		$row = fgetcsv( $this->fd );

		if( $row[ CFDOperation::TRADE_ID ] != 'Trade Id' ){
			$this->errors[] = "Falta el campo 'Trade Id'";
		}

		if( $row[ CFDOperation::INSTRUMENT_NAME ] != 'Instrument Name' ){
			$this->errors[] = "Falta el campo 'Instrument Name'";
		}

		if( $row[ CFDOperation::EXECUTION_TYPE ] != 'Execution Type' ){
			$this->errors[] = "Falta el campo 'Execution Type'";
		}

		if( $row[ CFDOperation::PRICE ] != 'Price' ){
			$this->errors[] = "Falta el campo 'Price'";
		}

		if( $row[ CFDOperation::QUANTITY ] != 'Quantity' ){
			$this->errors[] = "Falta el campo 'Quantity'";
		}

		if( $row[ CFDOperation::STATUS ] != 'Status' ){
			$this->errors[] = "Falta el campo 'Status'";
		}

		if( $row[ CFDOperation::RPL_CONVERTED ] != 'Rpl Converted' ){
			$this->errors[] = "Falta el campo 'Rpl Converted'";
		}

		if( $row[ CFDOperation::SWAP_CONVERTED ] != 'Swap Converted' ){
			$this->errors[] = "Falta el campo 'Swap Converted'";
		}

		if( $row[ CFDOperation::TIMESTAMP ] != 'Timestamp' ){
			$this->errors[] = "Falta el campo 'Timestamp'";
		}		
	}
}
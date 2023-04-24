<?php

class CFDsProcessor {
	const OUTPUT_FILENAME = 'CFDsOperations';

	protected $pathFile;
	protected $fd;
	
	public function __construct( $argc, $argv ){		
		if( $argc < 2 ){
			throw new \Exception( "php " . basename( __FILE__ ) . " <filename.csv>" );
		}
		
		$this->pathFile = $argv[1];
		if( !file_exists( $this->pathFile ) ){
			throw new \Exception( "No se encuentra el fichero " . $this->pathFile );
		}
	}

	public function process(){
		try {
			$this->validateFile();
			$this->processFile();

		} catch( CFDFileValidatorException $e ){
			echo $e->getMessage() . "\n";
			foreach( $e->getErrors() as $error ){
				echo $error . "\n";
			}

		} catch( \Exception $e ){
			echo $e->getMessage() . "\n";
		}
	}

	protected function validateFile(){
		$validator = new CFDFileValidator( $this->pathFile );
		$validator->validate();
	}

	protected function processFile(){
		$this->fd = fopen( $this->pathFile, 'r' );

		$data = [];
		$header = 0;
		$line = 0;
		while( $row = fgetcsv( $this->fd ) ){
			if( $header == $line ){
				$line++;
				continue;
			}

			$op = new CFDOperation( $row );
			if( isset( $data[ $op->getId() ] ) ){
				$op = $data[ $op->getId() ];
			}

			$op->processOperation( $row );
			$data[ $op->getId() ] = $op;
		}

		fclose( $this->fd );

		$this->generateOutputFile( $data );
	}

	protected function generateOutputFile( $data ){
		$fd = fopen( __DIR__ . DIRECTORY_SEPARATOR . self::OUTPUT_FILENAME . ".csv", 'w' );

		$separator = "\t";

		$header = [];

		$header[] = "Trade Id";
		$header[] = "Market";
		$header[] = "Opened Datetime";
		$header[] = "Closed Datetime";
		$header[] = "Quantity";
		$header[] = "Opened Price";
		$header[] = "Closed Price";
		$header[] = "Result";
		$header[] = "Swap";
		$header[] = "Income";
		
		fputcsv( $fd, $header, $separator );

		foreach( $data as $op ){
			$row = [];

			$row[] = $op->getId();
			$row[] = $op->getMarket();
			$row[] = $op->getOpenedDateTime();
			$row[] = $op->getClosedDateTime();
			$row[] = $op->getQuantity();
			$row[] = $op->getOpenedPrice();
			$row[] = $op->getClosedPrice();
			$row[] = $op->getResult();
			$row[] = $op->getSwap();
			$row[] = $op->calculateIncome();

			fputcsv( $fd, $row, $separator );
		}

		fclose( $fd );
	}
}
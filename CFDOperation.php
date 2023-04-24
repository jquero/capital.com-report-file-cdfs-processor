<?php

class CFDOperation {
	const POSSITION_OPEN = 'OPENED';
	const POSSITION_CLOSE = 'CLOSED';
	const POSSITION_SWAP = 'SWAP';

	const TRADE_ID = 0;
	const INSTRUMENT_NAME = 4;
	const EXECUTION_TYPE = 7;
	const QUANTITY = 8;
	const PRICE = 9;
	const STATUS = 14;
	const RPL_CONVERTED = 16;
	const SWAP_CONVERTED = 18;
	const TIMESTAMP = 20;

	protected $id = null;
	protected $market = null;
	protected $openedDateTime = null;
	protected $closedDateTime = null;
	protected $openedPrice = 0;
	protected $closedPrice = 0;
	protected $quantity = 0;
	protected $result = 0;
	protected $swap = 0;

	public function __construct( $row ){
		$this->id = $row[ self::TRADE_ID ];
	}

	public function processOperation( $row ){
		if( $this->isOpenedOperation( $row[ self::STATUS ] ) ){
			$this->openedDateTime = $row[ self::TIMESTAMP ];
			$this->market = $row[ self::INSTRUMENT_NAME ];
			$this->quantity = $row[ self::QUANTITY ];
			$this->openedPrice = $row[ self::PRICE ];

		} elseif( $this->isClosedOperation( $row[ self::STATUS ] ) ) {
			$this->closedPrice = $row[ self::PRICE ];
			$this->closedDateTime = $row[ self::TIMESTAMP ];
			$this->closedPrice = $row[ self::STATUS ] == self::POSSITION_CLOSE ? $row[ self::PRICE ] : 0;
			$this->result = $row[ self::RPL_CONVERTED ];

		} elseif( $this->isSwapOperation( $row[ self::STATUS ] ) ) {
			$this->swap += $row[ self::SWAP_CONVERTED ];
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getMarket(){
		return $this->market;
	}

	public function getOpenedDateTime(){
		return $this->openedDateTime;
	}

	public function getClosedDateTime(){
		return $this->closedDateTime;
	}

	public function getOpenedPrice(){
		return $this->openedPrice;
	}

	public function getClosedPrice(){
		return $this->closedPrice;
	}

	public function getQuantity(){
		return $this->quantity;
	}

	public function getResult(){
		return $this->result;
	}

	public function getSwap(){
		return $this->swap;
	}

	public function isOpenedOperation( $operationType ){
		return $operationType == self::POSSITION_OPEN;
	}

	public function isClosedOperation( $operationType ){
		return $operationType == self::POSSITION_CLOSE;
	}

	public function isSwapOperation( $operationType ){
		return $operationType == self::POSSITION_SWAP;
	}

	public function calculateIncome(){
		return $this->result + $this->swap;
	}
}
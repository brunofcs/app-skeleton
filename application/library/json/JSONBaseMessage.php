<?php
/**
 *	This class have base fields neeed for all JSON comunication between client 
 *  and server
 */

class JSONBaseMessage {
	
	/**
	 * Type of Message. All Values in JSONType
	 */
	public $type;

	/**
	 * Field that receive focus after message process
	 */
	public $fieldFocus;
}
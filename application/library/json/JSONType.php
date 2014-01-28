<?php
/*
 * Message types used in JSONMEssage->type
 */
 
class JSONType {
	
	// Redirection Message
	const REDIRECT       = 1;
	
	// Form Message
	const FORMVALIDATION = 2;

	// Exception Message
	const EXCEPTION      = 3;

	// Fill form Message
	const FORM           = 4;
	
	// Clear form Message
	const CLEARFORM      = 5;

	// Data Grid Message
	const GRID       	 = 6;

	// Question Message Alert
	const QUESTION       = 7;

}
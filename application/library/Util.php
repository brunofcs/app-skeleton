<?php

class Util {

	public static function dataDiff($oldRowData, $newRowData = array()) {
		return json_encode(array_diff_assoc($oldRowData, $newRowData));
	}

	public static function getRealIpAddr() {

		//check ip from share internet
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		  
		  $ip=$_SERVER['HTTP_CLIENT_IP'];

		//to check ip is pass from proxy
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

		  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

		} else {

		  $ip = $_SERVER['REMOTE_ADDR'];

		}

		return $ip;
	}

}
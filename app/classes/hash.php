<?php
	class hash {
	
	public static function make($string, $salt = ''){
		return hash('sha256', $string, $salt);
	}
	
	public static function salt() {
		return mcrypt_create_iv($length);
	}
	
	public static function unique() {
		return self::make(uniqueid());
	}
	
	}
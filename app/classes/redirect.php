<?php
class redirect {
	private $_From;
		
	public static function to($location = null) {
		if($location) {
			/*if(is_numeric($location)) {
				switch($location) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include 'inc/errors/404.php';
						exit;
					break;
				}
			}*/
			header('location: ' . $location);
			exit();
		}
	}
	
	public function fromURL($fromurl = null) {
		$this->_From = $fromurl;
	}	
	
	public function from() {
		if(!_From === null) {
			$this->_From = "True";
		} else {
			$this->_From = "false";
		}
		return $this->_From;
	}
}
<?php

class user {
	private $_db,
			$_data,
			$_sessionName,
			$_isLoggedIn;
	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		$this->_sessionName = config::get('session/session_name');	
		
		if(!$user){
			if(session::exists($this->_sessionName)){
				$user = session::get($this->_sessionName);
				if($this->find($user)){
					$this->_isLoggedIn = true;
				} else {
					//process logout
				}
			} 
		}else {
			$this->find($user);
		}
	//end construct function
	}
	public function create($fields = array()){
		if(!$this->_db->insert('tbl_user', $fields)) {
			throw new Exception('Could not register user');
		}
	}
	public function find($user = null){
		if($user) {
				// for this to properly work validation on user name has to only permit 			
				//alphanumeric usernames.
				$field = (is_numeric($user)) ? 'usr_id' : 'usr_email';
				$data = $this->_db->get('view_user', array($field, '=', $user));
				if($data->count()) {
					$this->_data = $data->first();
					return true;	
				}
		}
		return false;
	}
	public function login($username = null, $password = null, $rememeber = false) {	
		$user = $this->find($username);
		//print_r($this->_data);
        //echo '"'.$password.'"';
		if($user){
            //$this->data()->usr_password
            if(password_verify($password, $this->data()->usr_password)) {
				session::put($this->_sessionName, $this->data()->usr_email);
				return true;
			}
		}
		return false;
	}
	public function logout() {
			session::delete($this->_sessionName);
	}
	public function data(){
		return $this->_data;
	}
	public function isLoggedIn() {
		return $this->_isLoggedIn;	
	}
/*end user class*/
}
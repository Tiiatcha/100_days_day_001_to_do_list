<?php

class formChecks {
    private static $_form_data = null;
    private $result,
            $check = true,
            $user,
            $user_level = 0,
            $user_req_level,
            $errors,
            $verror;

    function __construct($data,$user_req_level){
        self::$_form_data = $data;
        $this->_result = array();
        $this->_user = new user();
        $this->_errors = array();
        $this->_verror = array();
        
    }
    public function runCheck($validationValues = array()){
        if($this->_user->isLoggedin()){
            $this->_user_level = $this->_user->data()->usr_permission;
            if($validation($validationValues)){
                $this->_check = true;
            } else {
                $this->_result = $errors->result(false, 'input', false);
                $this->_check = false;
            }
        } else {
            $this->_check = false;
            $this->_result = $errors->result(false, 'user', false);
        }
        return $this->_check;
    }
    private function validation($validationChecks){
        $validate = new Validate();
        $verror = $validate->check($_POST,$validationChecks);
        if($validation->passed(){
            $this->_check = true;
        } else {
            $this->_check = false;
            $result = $errors->result(false, 'input', false);
        }
    }

}
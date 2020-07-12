<?php
date_default_timezone_set('Europe/London');
header('Content-Type: application/json');
require_once'../core/init.php';
$time = date('Y-m-d H:i:s');
$result = array();
$verror = array();
$errors = new errors();
//$current_user = new user();

//if(!$current_user->isLoggedin()){$result = $errors->result(false, 'user', false);}
if(count($result) != 0 && !input::exists(0)){$result = $errors->result(false, 'input', false);}
if(count($result) != 0 && !Token::check(input::get('token'))){$result = $errors->result(false, 'token', false);}


$validate = new Validate();
$validation = $validate->check(
    $_POST,array(
    'todo_action' => array('required' => true),
    )
);

if(count($result) != 0 && $validation->passed()){
    foreach($validation->errors() as $error) {
        $verror[] = $error;
    }
    $result = $errors->result(false, 'validation', false,'','',$verror);
}
if(count($result) == 0)
{   
    // If all checks out do the following
    
    $table = 'todo_items';
    $prefix = 'todo_';
    $fields = array();
    foreach( $_POST as $k => $v) {
        $prefix_len = strlen($prefix);

        if (!strpos($k, $prefix)) {
            $field = substr($k,$prefix_len+1);
            $fields[$k] = $v;
        }
    }
    if(count($fields))
    {
        $fields['added'] = $time;
        // instantiate a new database connection
        // $db = new DB();
        //insert new record into database
        // $sqlcommit = $db->insert($table,$fields);
        $todo = new todo();
        print_r($tod);
        $add = $todo->create($fields);
        if($add){
            $msg = 'Checklist item added!';
            $result = $errors->result(true,'sucess',true, 'Success', $msg);
        } else {
            $result = $errors->result(false,'update',true, 'Update Failed', 'There was a problem updating the database!' );
        } 
    }
    else
    {
        $result = $errors->result(false, 'input', false);
    }
}
echo json_encode($result);
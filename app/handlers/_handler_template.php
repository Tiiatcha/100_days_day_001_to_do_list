<?php
date_default_timezone_set('Europe/London');
header('Content-Type: application/json');
require_once'../core/init.php';

$result = array();
$verror = array();
$errors = new errors();
$current_user = new user();

if(!$current_user->isLoggedin()){$result = $errors->result(false, 'user', false);}
if(count($result) != 0 && !input::exists(0)){$result = $errors->result(false, 'input', false);}
if(count($result) != 0 && !Token::check(input::get('token'))){$result = $errors->result(false, 'token', false);}


$validate = new Validate();
$validation = $validate->check(
    $_POST,array(
    'isu_rpt_id' => array('required' => true),
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
    
}
echo json_encode($result);
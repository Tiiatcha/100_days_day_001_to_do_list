<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
ini_set('error_reporting',E_ALL);
session_start();

$app_name = '100_Days_of_Code\\day_001_to_do_list';
$real_path = realpath($_SERVER["DOCUMENT_ROOT"]);
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'localhost',
		'username' => 'zombie',
		'password' => 'zombie',
		'db' => '100days__todo_list'	
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
		),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	),
	'app' => array(
		'name' => 'To Do',
		'system_path' => realpath($_SERVER["DOCUMENT_ROOT"]).'\\100_Days_of_Code\\day_001_to_do_list\\',
		'root' => $root.'/'.$app_name.'/'//realpath($_SERVER['HTTP_HOST']).'/checklist/'
	)
);
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

spl_autoload_register(function($class) {
		$root = realpath($_SERVER["DOCUMENT_ROOT"])."\\100_Days_of_Code\\day_001_to_do_list";
		require_once $root.'\\app\\classes\\' . $class . '.php';
});	


require_once $root.'\\checklist\\app\\functions\\sanitize.php';

if(cookie::exists(config::get('remember/cookie_name')) && !session::exists(config::get('session/session_name'))) {
	$hash = cookie::get(config::get('remember/cookie_name'));
	$hashcheck = DB::getInstance()->get('tbl_user_session', array('ses_hash', '=', $hash));
	if($hashcheck->count()) {
		$user = new user($hashcheck->first()->ses_user_id);
		$user->login();
	}
}
?>

<?php
session_start();
$app_name = 'checklist_2';
$real_path = realpath($_SERVER["DOCUMENT_ROOT"]);
$root = "${real_path}/${app_name}";
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'dean',
		'password' => 'GDMITeam01',
		'db' => 'pex_hub'
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
		'name' => 'checklist_2',
		'system_path' => realpath($_SERVER["DOCUMENT_ROOT"]).'\\checklist_2\\',
		'root' => $root//realpath($_SERVER['HTTP_HOST']).'/checklist_2/'
	)
);
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

spl_autoload_register(function($class) {
		$root = realpath($_SERVER["DOCUMENT_ROOT"])."/checklist_2";
		require_once $root.'/app/classes/' . $class . '.php';
});	


require_once $root.'/checklist_2/app/functions/sanitize.php';

if(cookie::exists(config::get('remember/cookie_name')) && !session::exists(config::get('session/session_name'))) {
	$hash = cookie::get(config::get('remember/cookie_name'));
	$hashcheck = DB::getInstance()->get('tbl_user_session', array('ses_hash', '=', $hash));
	if($hashcheck->count()) {
		$user = new user($hashcheck->first()->ses_user_id);
		$user->login();
	}
}
?>

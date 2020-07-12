<?php
require_once'../core/init.php';
$token = new Token();
echo $token::generate();
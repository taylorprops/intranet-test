<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');


$tp_email = $_POST['tp_email'];
$tp_pass = $_POST['tp_pass'];
$new_email = $_POST['new_email'];
$new_pass = $_POST['new_pass'];
$new_server = $_POST['new_server'];
$username = $_SESSION['S_Username'];
$user_id = $_SESSION['S_ID'];

$cmd = '/usr/bin/php /var/www/annearundelproperties.net/new/agents/ajax/email/transfer_to_new_account_script.php '.escapeshellarg($new_email).' '.escapeshellarg($new_pass).' '.escapeshellarg($new_server).' '.escapeshellarg($tp_email).' '.escapeshellarg($tp_pass).' '.escapeshellarg($username).' '.escapeshellarg($user_id).' /dev/null &';

exec($cmd);

die();

?>
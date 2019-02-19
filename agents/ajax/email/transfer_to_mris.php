<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$mris_email = $_POST['mris_email'];
$mris_pass = $_POST['mris_pass'];
$tp_email = $_POST['tp_email'];
$tp_pass = $_POST['tp_pass'];

$cmd = '/usr/bin/php /var/www/annearundelproperties.net/new/agents/ajax/email/transfer_to_mris_script.php '.escapeshellarg($mris_email).' '.escapeshellarg($mris_pass).' '.escapeshellarg($tp_email).' '.escapeshellarg($tp_pass).' >/dev/null &';

exec($cmd);

die();

?>
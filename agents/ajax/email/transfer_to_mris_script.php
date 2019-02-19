<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$mris_email = $argv[1];
$mris_pass = $argv[2];
$tp_email = $argv[3];
$tp_pass = $argv[4];

$cmd = 'imapsync --host1 imap.mris.com  --user1 '.$mris_email.' --password1  "'.$mris_pass.'" --host2 mail.tpmailserver.com  --user2 '.$tp_email.' --password2  "'.$tp_pass.'"';

exec($cmd, $output, $return_var);

sendMail('', $tp_email, 'Taylor Properties <internal@taylorprops.com>', 'Your email transfer is complete', 'All of your emails have been transferred from your mris.com account to your taylorprops.com or annearundelproperties.com account', '', '', '');
sendMail('', 'mike@taylorprops.com', 'Taylor Properties <internal@taylorprops.com>', 'Your email transfer is complete', $cmd.'<br><br>'.print_r($output).'<br><br>'.$return_var, '', '', '');
?>
<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');

/*
$new_email = $argv[1];
$new_pass = $argv[2];
$new_server = $argv[3];
$tp_email = $argv[4];
$tp_pass = $argv[5];
$username = $argv[6];
$user_id = $argv[7];


$new_email = 'taylorpropertiestestemail@gmail.com';
$new_pass = 'T@yl0rprops';
$new_server = 'gmail';
$tp_email = 'test@taylorprops.com';
$tp_pass = 'T@yl0rprops';
*/

$tp_email = $_POST['tp_email'];
$tp_pass = "'".$_POST['tp_pass']."'";
$new_email = $_POST['new_email'];
$new_pass = "'".$_POST['new_pass']."'";
$new_server = $_POST['new_server'];
$other_server = $_POST['other_server'];
$username = $_SESSION['S_Username'];
$user_id = $_SESSION['S_ID'];



$server = "216.55.138.203";

//https://github.com/phpseclib/phpseclib
$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('phpseclib\\', '/var/www/annearundelproperties.net/vendor/phpseclib/phpseclib');
$loader->register();

use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

//$key = new RSA();
//$key->loadKey(file_get_contents('private-key.txt'));

$ssh = new SSH2($server);
if (!$ssh->login('mike', 'T0m@hawkT@ters')) {
    exit('Login Failed');
}

if($_SESSION['S_ID'] == '3193') {
    //$dry = '--dry --justfolders';
    $dry = '';
} else {
    $dry = '';
}

$gmail = '';
$yahoo = '';
if($new_server == 'gmail') {

    $host = 'imap.gmail.com';
    $gmail = 'Due to daily limits by gmail you can ony transfer 500MB of emails per day. If all of your emails have not been transferred, please rerun the transfer tomorrow and each additional day as needed.';

    $cmd = 'imapsync --host1 mail.tpmailserver.com --user1 '.$tp_email.' --password1 '.$tp_pass.' --host2 '.$host.' --user2 '.$new_email.' --password2 '.$new_pass.' --ssl2  --port2 993 --authmech1 PLAIN --authmech2 PLAIN --syncinternaldates --maxsize 25000000 --automap --addheader --exclude "Deleted\sItems" --exclude "Deleted Messages" --exclude "Emailed Contacts" --exclude "Junk" --exclude "Trash" --exclude "spam" --regextrans2 "s/INBOX\/([.]*)/$1/" --regextrans2 "s/[ ]+/_/g" --regextrans2 "s,^Sent/,Sent Mail/," '.$dry;

} else if($new_server == 'yahoo') {

    $host = 'imap.mail.yahoo.com';
    $yahoo = '<br><br>Other reasons - To transfer to a yahoo.com account you must first login to your yahoo.com account and go to <a class="light" href="https://login.yahoo.com/account/security#other-apps" target="_blank">https://login.yahoo.com/account/security#other-apps</a>.<br><br> Once there turn on "Allow apps that use less secure sign in".';

    $cmd = 'imapsync --host1 mail.tpmailserver.com --user1 '.$tp_email.' --password1 '.$tp_pass.' --host2 '.$host.' --user2 '.$new_email.' --password2 '.$new_pass.' --ssl2  --port2 993 --authmech1 PLAIN --authmech2 PLAIN --syncinternaldates --automap --addheader --exclude "Deleted\sItems" --exclude "Deleted Messages" --exclude "Emailed Contacts" --exclude "Junk" --exclude "Trash" --exclude "spam" --regextrans2 "s/INBOX\/([.]*)/$1/" '.$dry;

} else if($new_server == 'outlook') {

    $host = 'imap-mail.outlook.com';

    $cmd = 'imapsync --host1 mail.tpmailserver.com --user1 '.$tp_email.' --password1 '.$tp_pass.' --host2 '.$host.' --user2 '.$new_email.' --password2 '.$new_pass.' --ssl2  --port2 993 --authmech1 PLAIN --authmech2 PLAIN --syncinternaldates --automap --addheader --exclude "Deleted\sItems" --exclude "Deleted Messages" --exclude "Emailed Contacts" --exclude "Junk" --exclude "Trash" --exclude "spam" --regextrans2 "s/INBOX\/([.]*)/$1/" '.$dry;

} else if($new_server == 'other') {

    $host = $other_server;


    $cmd = 'imapsync --host1 mail.tpmailserver.com --user1 '.$tp_email.' --password1 '.$tp_pass.' --host2 '.$host.' --user2 '.$new_email.' --password2 '.$new_pass.' --ssl2  --port2 993 --authmech1 PLAIN --authmech2 PLAIN --syncinternaldates --addheader --exclude "Deleted\sItems" --exclude "Deleted Messages" --exclude "Emailed Contacts" --exclude "Junk" --exclude "Trash" --exclude "spam" --regextrans2 \'s/^INBOX\.(.+)/INBOX.$1/\' --regextrans2 \'s/^INBOX\.INBOX(.+)/INBOX$1/\' --automap '.$dry;

}

$file = '/programs/email_transfer/'.$user_id.'_'.preg_replace($_GLOB_bad_characters, '_', $username).'_'.date('YmdHis').'.txt';
$ssh->exec('cat > '.$file.' <<EOF



[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

'.$username.' - '.$user_id.'
'.$tp_email.' - '.$tp_pass.' - '.$host.' - '.$new_email.' - '.$new_pass);


$ssh->exec($cmd.' >> '.$file);

$results1 = $ssh->exec('if grep "not\senabled\sfor\sIMAP\suse" '.$file.'; then echo errors_found; fi');
if(stristr($results1, 'errors_found')) {
    $errors = '<div style="width: 100%; text-align: left">ERROR - To transfer to a gmail.com account you must first login to your gmail.com account and go to your Settings.<br><br>Click on the gear icon in the top right and selecting "Settings".<br><br>From there select "Forwarding and POP/IMAP".<br><br>In the "IMAP Access" section select "Enable IMAP" and select "Save Changes" at the bottom.</div>';
};

if($errors == '') {
    $results2 = $ssh->exec('if grep -E "Error\slogin\son\s\[mail\.tpmailserver\.com\]" '.$file.'; then echo errors_found; fi');
    if(stristr($results2, 'errors_found')) {
        $errors = 'ERROR - Incorrect TaylorProps.com Email or Password';
    };
}
if($errors == '') {
    $results3 = $ssh->exec('if grep -E "Error\slogin\son\s\[" '.$file.'; then echo errors_found; fi');
    if(stristr($results3, 'errors_found')) {
        $errors = '<div style="width: 100%; text-align="left"">ERROR - Incorrect Password for '.$new_email.' '.$yahoo.'</div>';
    };
}
if($errors == '') {
    $ssh->exec('echo "Email Transfer Started by Agent" | mutt -a "'.$file.'" -s "Email Transfer Started by Agent" -- mike@taylorprops.com \
echo "Email Transfer Has Begun. Your transfer from '.$tp_email.' to '.$new_email.' has begun. '.$gmail.' Make sure to check and see if all messages have transferred. If you have any issues call Mike Taylor at 301-970-2447." | mutt -s "Email Transfer Complete" -- '.$tp_email);
}

echo $errors;


?>

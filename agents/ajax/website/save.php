<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$bio = trim($db -> quote($_POST['bio']));
$theme = trim($db -> quote($_POST['theme']));
$testimonials = trim($db -> quote($_POST['testimonials']));
$designations = trim($db -> quote($_POST['designations']));
$personal_website = trim($db -> quote($_POST['personal_website']));
$remove = array('/www\./', '/http:\/\//', '/https:\/\//');
$personal_website = preg_replace($remove, '', $personal_website);
$personal_website = 'http://'.$personal_website;


$update = "update company.tbl_agents".$test." set agentBio = '".$bio."', Styles = '".$theme."', testimonials = '".$testimonials."', designations = '".$designations."', personal_website = '".$personal_website."' where id = '".$_SESSION['S_ID']."'";
$queryResults = $db -> query($update);

$updateDes = "update company.tbl_agents set designations = replace(designations, '®', '&reg');";
$resultsQuery = $db->query($updateDes);
?>

<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');


$agent_id = $_POST['agent_id'];
$session_id = $_POST['session_id'];


$upload_dir = '/var/www/annearundelproperties.net/new/agents/ajax/convert/tmp/'.$agent_id.'/';
if(!is_dir($upload_dir)) {
	mkdir($upload_dir);
	chmod($upload_dir, 0777);
}
//exec('rm -r /var/www/annearundelproperties.net/new/agents/ajax/convert/tmp/'.$agent_id.'/');
$upload_dir = '/var/www/annearundelproperties.net/new/agents/ajax/convert/tmp/'.$agent_id.'/'.$session_id.'/';
if(!is_dir($upload_dir)) {
	mkdir($upload_dir);
	chmod($upload_dir, 0777);
}


if (!empty($_FILES)) {
	$temp_file   = $_FILES['Filedata']['tmp_name'];
	$file = $_FILES['Filedata']['name'];
	$info = pathinfo($file);
	$f_ext = $info['extension'];
	$f_name =  basename($file,'.'.$info['extension']);

	$file_name = preg_replace($_GLOB_bad_characters, '', $f_name);

	$target_file = $upload_dir . $file_name.'.'.$f_ext;

	move_uploaded_file($temp_file, $target_file);
	chmod($target_file, 0777);

	exec('libreoffice --headless --writer --convert-to pdf --outdir '.$upload_dir.' '.$target_file);

	chmod($upload_dir . $file_name.'.pdf', 0777);
	echo 1;

}
?>

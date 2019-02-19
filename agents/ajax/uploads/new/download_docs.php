<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');


$agent_id = $_SESSION['S_ID'];

$path = '/var/www/annearundelproperties.net/new/agents/uploads/uploads/'.$agent_id;
if(!is_dir($path)) {
	mkdir($path);
}

?>

** You will need a program to open the compressed files after downloading. I recommend 7zip. You can download it at <a href="https://www.7-zip.org/download.html" target="_blank">https://www.7-zip.org/download.html</a><br>
If you're not sure which to download just download the first one.<br><br>
<input type="text" id="search" placeholder="Filter by Address or MLS" style="width: 250px;">
<?php
$dir = new DirectoryIterator($path);
foreach ($dir as $fileinfo) {
    if ($fileinfo->isDir() && !$fileinfo->isDot()) {
        $mls = $fileinfo->getFilename();

		$uploadQuery = "SELECT upload_street, upload_city, upload_state, upload_zip FROM company.mls_uploads where upload_agent_id = '".$agent_id."' and upload_mls like '".str_replace(':MRIS', '', $mls)."' order by upload_type DESC";
		$upload = $db -> select($uploadQuery);

		if(count($upload) > 0) {
			echo '<div class="download_div" data-info="'.$mls.' '.$upload[0]['upload_street'].' '.$upload[0]['upload_city'].' '.$upload[0]['upload_state'].' '.$upload[0]['upload_zip'].'">';
			echo '<h4>'.$mls.' - '.$upload[0]['upload_street'].' '.$upload[0]['upload_city'].', '.$upload[0]['upload_state'].' '.$upload[0]['upload_zip'].'</h4>';
			$tmp = '/var/www/annearundelproperties.net/new/agents/uploads/tmp/'.$agent_id;
			if(!is_dir($tmp)) {
				mkdir($tmp);
			}
			$address = preg_replace($_GLOB_bad_characters, '_', $upload[0]['upload_street'].'-'.$upload[0]['upload_city'].'-'.$upload[0]['upload_state'].'-'.$upload[0]['upload_zip']);

			exec('tar -zcvf /var/www/annearundelproperties.net/new/agents/uploads/tmp/'.$agent_id.'/'.$mls.'-'.$address.'.tar.gz -C '.$path.'/ '.$mls);

			echo '<a href="https://annearundelproperties.net/new/agents/uploads/tmp/'.$agent_id.'/'.$mls.'-'.$address.'.tar.gz" target="_blank" class="button button_download">Download All Files</a>';
			echo '</div>';
		}

    }
}
?>


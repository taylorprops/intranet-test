<?php
		
include('/var/www/annearundelproperties.net/new/includes/global.php');

$dir = $_POST['folder'];
$dh  = opendir($dir);
if(is_dir($dir)) {
	while (false !== ($filename = readdir($dh))) {
		$info = new SplFileInfo($filename);
		$ext = $info->getExtension();
		if($ext == 'pdf') { 
			$files[] = $filename;
		}
	}
} else {
	$files = array();
}
?>
<?php 
if(count($files) > 0) { ?>
<table width="100%" id="file_table">
	<tr>
    	<td></td>
    	<td style="padding-bottom: 15px;"><a href="javascript:void(0)" id="delete" class="button button_cancel_big" style="font-size: 14px;">Delete All Files</a></td>
    </tr>

<?php 
for($f=0;$f<count($files);$f++) {
	$dir = str_replace('/var/www/', 'https://', $dir);		
?>
	
 	<tr>
    	<td><a href="<?php echo $dir.'/'.$files[$f]; ?>" class="file_link" target="_blank"><?php echo $files[$f]; ?></a></td>
        <td><a href="<?php echo $dir.'/'.$files[$f]; ?>" class="b" target="_blank">Download PDF</a></td>
    </tr> 	
    
<?php 
} ?>
<tr><td colspan="2" height="30"></td></tr>
<?php if(count($files) > 1) { ?>
<tr>
	<td colspan="2" style="padding-bottom: 15px;"><a href="javascript: void(0)" class="button button_combine" id="combine" style="width: 250px;">Combine All Into One File</a></td>
</tr>
<tr>
    <td colspan="2"><a href="javascript: void(0)" class="button button_download" id="zip" style="width: 250px;">Zip and Download All</a></td>
</tr>
<?php } else { ?>
<tr>
    <td colspan="2"><div class="instructions_small">To Save: Right click on link and select "Save As" or "Save Link As"</div></td>
</tr>

<?php } ?>
</table>

<?php } else { ?>
No Files Uploaded For Conversion Yet
<?php } ?>
<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$formQuery = "select * from company.tbl_forms where category =  'required' order by description, view_type";
$form = $db -> select($formQuery);
$formCount = count($form);

?>

<div id="table_container">
	<div class="forms_header">Required In-House Forms</div>
    <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Description</th>
                <th>Type</th>
                <th>Region</th>
                <th>Sale/Rental</th>
                
            </tr>
        </thead>
        <tbody>
        	<tr>
            	<td colspan="5" style="font-size: 16px; font-weight:bold; color:#D18359;">Editable Forms</td>
            </tr>
            <?php 
			for($f=0;$f<$formCount;$f++) {
				if($form[$f]['view_type'] == 'edit') { ?>
            <tr>
                <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                <td><?php echo $form[$f]['description']; ?></td>
                <td><?php if($form[$f]['view_type'] == 'print') { echo 'Print Only'; } else { echo 'Editable'; } ?></td>
                <td>All Regions</td>
                <td><?php if($form[$f]['prop_type'] != '') { echo $form[$f]['prop_type']; } else { echo 'Sale and Rental'; } ?></td>
                
            </tr>
            <?php }
			}?>
        </tbody>
    </table>
</div>
<div style="display: block; height:100px; width:100%;"></div>

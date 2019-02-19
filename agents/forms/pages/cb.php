<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$formQuery = "select * from company.tbl_forms where category = 'breakdowns' and commission_plan = '".$_SESSION['S_Plan']."' and description like '%".$_SESSION['S_Commission']."%' order by view_type";
$form = $db -> select($formQuery);
$formCount = count($form);

for($f=0;$f<$formCount;$f++) { 
	if($form[$f]['view_type'] == 'print') {
		$cb_print_link = '/new/ajax/file_download/file_download.php?filename='.urlencode(str_replace(' ', '_', $form[$f]['description'])).'.pdf&fileloc=https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path'];
	} else if($form[$f]['view_type'] == 'edit') {
		$cb_editable_link = '/new/ajax/file_download/file_download.php?filename='.urlencode(str_replace(' ', '_', $form[$f]['description'])).'.pdf&fileloc=https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path'];
	}
}
?>

<div id="table_container">
	<div class="forms_header">Commission Breakdowns</div>
    <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Type</th>
                <th>Sale/Rental</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a class="button button_download" href="<?php echo $cb_editable_link; ?>" target="_blank">Download </a></td>
                <td>Editable/Auto Calculate</td>
                <td>Sale and Rental</td>
                <td>Editable Commission Breakdown</td>
            </tr>
            <tr>
                <td><a class="button button_download" href="<?php echo $cb_print_link; ?>" target="_blank">Download </a></td>
                <td>Print Only</td>
                <td>Sale and Rental</td>
                <td>Print Only Commission Breakdown</td>
            </tr>
        </tbody>
    </table>
        
</div>
<div style="display: block; height:100px; width:100%;"></div>


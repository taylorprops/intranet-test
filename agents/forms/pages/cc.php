<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$formQuery = "select * from company.tbl_forms where category = 'Checklists - Contracts' and prop_type = 'Sale' order by state, county, description";
$form = $db -> select($formQuery);
$formCount = count($form);
?>

<div id="table_container">
	<div class="forms_header">Contract Checklists</div>
    <div id="form_tabs">
        <ul>
            <li><a href="#MD">MD Checklists</a></li>
            <li><a href="#VA">VA Checklists</a></li>
            <li><a href="#DC">DC Checklists</a></li>
        </ul>
        <div id="MD">
            <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Description</th>
                        <th>Region</th>
                        <th>Sale/Rental</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($f=0;$f<$formCount;$f++) { 
				if($form[$f]['state'] == 'MD') { ?>
                    <tr>
                        <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                        <td><?php echo $form[$f]['description']; ?></td>
                        <td><?php if($form[$f]['county'] != '') { echo $form[$f]['county']; } else { echo 'All Counties'; } ?></td>
                        <td><?php echo $form[$f]['prop_type']; ?></td>
                        
                    </tr>
                    <?php }
			}?>
                </tbody>
            </table>
        </div>
        <div id="VA">
            <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Description</th>
                        <th>Region</th>
                        <th>Sale/Rental</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($f=0;$f<$formCount;$f++) { 
				if($form[$f]['state'] == 'VA') { ?>
                    <tr>
                        <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                        <td><?php echo $form[$f]['description']; ?></td>
                        <td><?php if($form[$f]['county'] != '') { echo $form[$f]['county']; } else { echo 'All Counties'; } ?></td>
                        <td><?php echo $form[$f]['prop_type']; ?></td>
                        
                    </tr>
                    <?php }
			}?>
                </tbody>
            </table>
        </div>
        <div id="DC">
            <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Description</th>
                        <th>Region</th>
                        <th>Sale/Rental</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($f=0;$f<$formCount;$f++) { 
				if($form[$f]['state'] == 'DC') { ?>
                    <tr>
                        <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                        <td><?php echo $form[$f]['description']; ?></td>
                        <td><?php if($form[$f]['county'] != '') { echo $form[$f]['county']; } else { echo 'All Counties'; } ?></td>
                        <td><?php echo $form[$f]['prop_type']; ?></td>
                        
                    </tr>
                    <?php }
			}?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div style="display: block; height:100px; width:100%;"></div>


<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');



$agent_id = $_SESSION['S_ID'];
$session_id = session_id();
$upload_dir = '/var/www/annearundelproperties.net/new/agents/ajax/convert/tmp/'.$agent_id.'/'.$session_id.'/';

?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>Convert to PDF</title>

<?php 

include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
.instructions { font-size: 23px; color: #D18359; margin-bottom: 10px; }
.instructions_small { font-size: 16px; color: #516A89; margin-bottom: 20px; }
#file_table td {  padding: 5px; border-bottom: 1px dotted #ccc;  }
#file_table td a { font-size: 18px; color: #516A89;}
#file_table td a:hover { color: ##6381A5; }
#file_table td a:visited { color: ##6381A5; }
.uploadifive-queue-item { width: 500px; border: 1px solid #ccc !important; }
input[type="file"] { height: 50px; }
</style>
<script type="text/javascript" src="https://annearundelproperties.net/new/scripts/upload/jquery.uploadifive.min.js"></script>
<link href="https://annearundelproperties.net/new/scripts/upload/uploadifive.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

<div class="body_container">
	<h2>Convert to PDF</h2>
    <br><br><br><br>
    <table width="100%">
    	<tr>
        	<td width="50%" valign="top">
            <div class="instructions">To begin, select one or more files to convert to a PDF</div>
            <div class="instructions_small">Convert all word, excel, image and text files to PDF</div>
            <input type="file" id="convert">
            <br><br>
            <div id="queue"></div>
            </td>
            
            <td width="50%" valign="top">
            <div class="instructions">Converted files below</div>
           <img id="loader_image" src="/new/images/loading/loading.gif" style="display:none;">
            <div id="files_div"></div>
            </td>
        </tr>
    </table>
    
    

</div>

<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	//get_uploads();
	<?php $timestamp = time(); ?>
	$('#convert').uploadifive({
		'auto'             	: true,
		'fileSizeLimit' 	: '100MB',
		'width'				: '250',
		'method'   			: 'post',
		'buttonText'   		: 'Select Files',
		'removeCompleted' 	: false,
		'multi'				: true,
		'formData'			: { 
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo md5('unique_salt' . $timestamp); ?>',
			'session_id': '<?php echo $session_id; ?>',
			'agent_id'	: '<?php echo $agent_id; ?>'
		},
		'queueID'          : 'queue',
		'onSelect' : function() {
            $('#loader_image').show();
        },
		'onQueueComplete' 	: function() { 
			get_uploads();
		},
		'onUploadComplete' : function(){
			$('#loader_image').hide();
		},
		'uploadScript'     : '/new/agents/ajax/convert/convert.php'
	});
	
});

function get_uploads(){
	$.ajax({
		type: 'POST',
		data: { folder: '<?php echo $upload_dir; ?>' },
		url: '/new/agents/ajax/convert/get_files.php',
		success: function(response){
			$('#files_div').html(response);
			$('#delete').click(delete_all);
			$('#combine').click(combine);
			$('#zip').click(download_all);
		}
	});
}
function delete_all(){
	$.ajax({
		type: 'POST',
		data: { folder: '<?php echo $upload_dir; ?>' },
		url: '/new/agents/ajax/convert/delete_all.php',
		success: function(response){
			get_uploads();
			$('#queue').html('');
		}
	});
}
function combine(){
	$.ajax({
		type: 'POST',
		data: { folder: '<?php echo $upload_dir; ?>' },
		url: '/new/agents/ajax/convert/combine_all.php',
		success: function(response){
			get_uploads();
			$('#queue').html('');
		}
	});
}
function download_all() {
	$.ajax({
		type: 'POST',
		data: { folder: '<?php echo $upload_dir; ?>' },
		url: '/new/agents/ajax/convert/zip.php',
		success: function(response){
			window.open('<?php echo str_replace('/var/www/', 'https://', $upload_dir); ?>ZippeddFile.zip');
		}
	});
}
</script> 
</body>
</html>
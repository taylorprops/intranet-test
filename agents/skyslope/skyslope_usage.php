<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

$emailsQuery = "select * from skyslope.info_emails_sent order by date_sent DESC";
$emails = $db->select($emailsQuery);
$emailsCount = count($emails);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>SkySlope Information</title>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
.info_container { width: 100%; }
.info_section1 { width: 40%; border: 1px solid #ccc; float: left; padding: 25px;}
.info_section2 { width:52%; border: 1px solid #ccc; float: right; padding: 25px;}
.info_holder { width: 97%; margin: 15px auto; border: 1px solid #ccc; padding: 8px; }
.info_header { width: 100%; font-size: 18px; font-weight: bold; color: #516A89 }
.show_info { color: #3A4A63; text-decoration: none}
.info_body {  background: #f7f3ef; padding: 10px; line-height: 21px; margin-top: 15px;}
.body_div { display: none; }
</style>
</head>
<body>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

<div class="body_container">
	<h2>SkySlope Information</h2>
	<br><br>
	<h3 style="color: #325599">SkySlope Customer Support - (800) 507-4117</h3>
	<br><br>
	<h4>Emails Sent Regarding SkySlope</h4>
	<br>

	<div class="info_container">
		<div class="info_section1">
			<h4>SkySlope Information</h4>

			<div class="info_holder">
				<span class="info_header">Training</span> - <a href="javascript: void(0)" class="show_info">View Training Details</a>
				<div class="info_body">
					Everyone must attend training classes before using SkySlope. They constantly offer webinars and there is also a lot of information in their Tutorials Section found at <a href="https://support.skyslope.com/hc/en-us/categories/201506227-Using-SkySlope" target="_blank">https://support.skyslope.com/hc/en-us/categories/201506227-Using-SkySlope</a>.
			        <br><br>
			        Below are training webinars as well as a recording of a previous webinar.
			        <br><br>
			        SkySlope - Agent Training (~45 min)
			        <br><br>
			        Description: This live instructor-led webinar session is intended for individuals who are creating property files and uploading documents to those files in the system. Attendees will have the opportunity to ask the instructor questions during the webinar.
			        <br><br>
			        Every Wednesday - 4PM Registration URL: <a href="https://zoom.us/webinar/register/WN_1ugpRMQuTeWP6FjEDdsbdA" target="_blank">https://zoom.us/webinar/register/WN_1ugpRMQuTeWP6FjEDdsbdA</a>
			        <br><br>
			        Every Friday - 1PM Registration URL: <a href="https://zoom.us/webinar/register/WN_aJ-oTfmJRcaJrRFl6cGKsg" target="_blank">https://zoom.us/webinar/register/WN_aJ-oTfmJRcaJrRFl6cGKsg</a>
			        <br><br>
			        Once registered, you'll receive the meeting details such as the dial-in number and the Access Code.
			        <br><br>
			        RECORDING: <a href="https://www.youtube.com/watch?v=dv47Y00Y0X8&feature=youtu.be" target="_blank">https://www.youtube.com/watch?v=dv47Y00Y0X8&feature=youtu.be</a>
				</div>
			</div>

		</div>
		<div class="info_section2">
			<h4>Emails Sent</h4>
			<?php for($e=0;$e<$emailsCount;$e++) { ?>

				<div class="info_holder">
					<table width="100%">
						<tr>
							<td width="100" valign="top"><?php echo $emails[$e]['date_sent']; ?></td>
							<td>
								<div class="subject_div"><?php echo $emails[$e]['subject']; ?></div>
								<br>
								<a href="javascript:void(0)" class="button button_normal show_message">View Message</a>

								<div class="body_div">
									<hr>
									<?php echo $emails[$e]['message']; ?>
								</div>
							</td>
						</tr>
					</table>
				</div>

			<?php } ?>

		</div>

	</div>


</div>

<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');

	$('.show_message').click(function() {
		$(this).next('div.body_div').toggle();
	});
	$('.show_info').click(function() {
		$(this).next('div.info_body').toggle();
	});
});

</script>
</body>
</html>
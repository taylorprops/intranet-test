<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

//$meetingQuery = "select *, date_format(meeting_date, '%a, %M %D') as md from company.meeting_emails where meeting_date <= CURDATE()";
$meetingQuery = "select *, date_format(meeting_date, '%a, %M %D') as md from company.meeting_emails where meeting_date >= CURDATE()";
$meeting = $db -> select($meetingQuery);

$responseQuery = "select * from company.meeting_emails_replies where email_id = '".$meeting[0]['email_id']."' and agent_id = '".$_SESSION['S_ID']."'";
$response = $db -> select($responseQuery);

if(count($meeting) > 0) {
?>
<div class="sec_container">
    <div class="status_header">Upcoming Company Meeting</div>
    <strong>When:</strong> <?php echo $meeting[0]['md']; ?> from <?php echo $meeting[0]['meeting_start']; ?> to <?php echo $meeting[0]['meeting_end']; ?>
    <br>
    <strong>Location:</strong> <?php echo $meeting[0]['venue']; ?>
    <br>
    <div style="margin: 6px 0;">
    <a href="javascript: void(0)" id="show_meeting_details" class="dark">Show Details</a>
    </div>
    <div id="meeting_details" style="display: none; line-height: 120%;">
    <hr>
    <strong>Address:</strong> 
	<table width="100%">
		<tr>
			<td><?php echo $meeting[0]['street']; ?><br><?php echo $meeting[0]['city']; ?>, <?php echo $meeting[0]['state']; ?> <?php echo $meeting[0]['zip']; ?></td>
            <td valign="top" align="right"><strong><a href="<?php echo $meeting[0]['map_link']; ?>" target="_blank" class="button button_normal">View Map</a></strong></td>
        </tr>
    </table>
    <strong>Details:</strong>
    <br>
	<?php echo $meeting[0]['message']; ?>
    </div>
    <hr>
    <?php if(count($response) > 0) {
        if($response[0]['response'] == 'yes') { ?>
        <table>
        	<tr>
            	<td><img src="/new/images/icons/smile.png"></td>
                <td>You are attending!</td>
            </tr>
            <tr>
            	<td colspan="2">Not able to attend? Click <a href="javascript: void(0)" class="dark attending" data-attending="no" data-responded="yes" style="font-size: 15px;">Here</a></td>
            </tr>
        </table>
        
        <?php } else { ?>
        
        <table>
        	<tr>
            	<td><img src="/new/images/icons/frown.png"></td>
                <td>You are not attending!</td>
            </tr>
            <tr>
            	<td colspan="2">Able to attend? Click <a href="javascript: void(0)" class="dark attending" data-attending="yes" data-responded="yes" style="font-size: 15px;">Here</a></td>
            </tr>
        </table>
        
        <?php } ?>
        
    <?php } else { ?>
    <table>
    	<tr>
        	<td colspan="2">
            	<table>
                	<tr>
                    	<td><img src="/new/images/icons/warning_red_new.png" height="30"></td>
            			<td>You have not responded yet.</td>
                    </tr>
                </table>
            </td>
        </tr>
    	<tr>
        	<td>Are you attending?</td>
            <td style="padding-left: 15px;"><a href="javascript: void(0)" class="button button_green attending" data-attending="yes" data-responded="no" style="width: 50px; text-align:center">Yes</a> 
            <a href="javascript: void(0)" class="button button_red attending" data-attending="no" data-responded="no" style="width: 50px; text-align:center">No</a></td>
        </tr>
    </table>
    <?php } ?>
</div>
<?php } ?>
<input type="hidden" id="email_id" value="<?php echo $meeting[0]['email_id']; ?>">
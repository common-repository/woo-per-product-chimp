<?php	


/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	
	$wppc_email_lists = get_option( 'wppc_email_lists' );
	$wppc_email_lists = array_reverse( $wppc_email_lists );

	if( empty( $wppc_email_lists ) ) {
		$wppc_email = get_option('wppc_email');
		$wppc_api_key = get_option('wppc_api_key');
		
		$data = array( 'fields' => 'lists' );
	 
		$url = 'https://' . substr($wppc_api_key,strpos($wppc_api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/';

		$mch = curl_init();
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '.base64_encode( 'user:'. $wppc_api_key )
		);
		curl_setopt($mch, CURLOPT_URL, $url );
		curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($mch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($mch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($mch, CURLOPT_TIMEOUT, 10);
		curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false);
	 
		$result = curl_exec($mch);
		$result = json_decode($result);
		$wppc_email_lists = !empty( $result->lists ) ? $result->lists : array();
		
		update_option('wppc_email_lists', $wppc_email_lists);
	}
?>

	<table class="wp-list-table widefat fixed striped tableexamples wppc_email_list_container">
		
		<thead>
			<tr>
				<th class="manage-column column-list-name column-primary">
					<a><span>List Name</span></a>
				</th>
				<th class="manage-column column-list-id column-primary">
					<a><span>List ID</span></a>
				</th>
				<th class="manage-column column-contact column-primary">
					<a><span>Contact</span></a>
				</th>
				<th class="manage-column column-list-stat column-primary">
					<a><span>Stat</span></a>
				</th>
				<th class="manage-column column-list-date column-primary">
					<a><span>Date</span></a>
				</th>
			</tr>
		</thead>
		
		<tbody id="the-list" data-wp-lists="list:tableexample">
		
			
		<?php 
		foreach( $wppc_email_lists as $list ) {
			
			$contact  = $list->contact->company;
			$contact .= $list->contact->address1 .', ';
			$contact .= $list->contact->city .', ';
			$contact .= $list->contact->state .', ';
			$contact .= $list->contact->country;
			
			$total_members = $list->stats->member_count;

			$date 		= $list->date_created;
			$date 		= explode("+", $date);
			$date 		= explode("T", $date[0]);
			$time 		= explode(":", $date[1]);
			$date 		= explode("-", $date[0]);
			$timeago	= human_time_diff( date('U', mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]) ), current_time('timestamp') ) . ' ago';
			$date 		= $date[2] .'/'. $date[1] .'/'. $date[0];
			
			echo '<tr>';
			echo '
			<td class="list-name has-row-actions column-list-name column-primary">
				<strong><a class="row-title" href="admin.php?page=wppc_email_list&lid='.$list->id.'">'.$list->name.'</a></strong>
				<div class="row-actions">
					<span class="edit"><a href="admin.php?page=wppc_email_list&lid='.$list->id.'">Edit</a> | </span>
					<span class="delete wppc_delete_list" lid="'.$list->id.'">Delete</span>
				</div>
			</td>';
			
			echo '<td class="list-id column-list-id">'.$list->id.'</td>';
			echo '<td class="contact column-contact">'.$contact.'</td>';
			echo '<td class="list-stat column-list-stat"><strong>Total Members: <i>'.$total_members.'</i></strong></td>';	
			echo '<td class="list-date column-list-date">'.$date.'<br><strong><i>'.$timeago.'</i></strong></td>';
			echo '</tr>';
		}
		
		
		?>
			
		</tbody>
		
		<tfoot>
			<tr>
				<th class="manage-column column-list-name column-primary">
					<a><span>List Name</span></a>
				</th>
				<th class="manage-column column-list-id column-primary">
					<a><span>List ID</span></a>
				</th>
				<th class="manage-column column-contact column-primary">
					<a><span>Contact</span></a>
				</th>
				<th class="manage-column column-list-stat column-primary">
					<a><span>Stat</span></a>
				</th>
				<th class="manage-column column-list-date column-primary">
					<a><span>Date</span></a>
				</th>
			</tr>
		</tfoot>
		
	</table>
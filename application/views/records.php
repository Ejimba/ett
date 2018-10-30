<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->view('header'); ?>

	<h1>Records</h1>
	<div class="table-responsive">
		<table class="table table-hover table-striped data-table">
			<thead>
				<th>Unique Key</th>
				<th>Rec Status</th>
				<th>Global Record Id</th>
				<th>First Save Logon Name</th>
				<th>First Save Time</th>
				<th>Last Save Logon Name</th>
				<th>Last Save Time</th>
				<th>Partner</th>
				<th>County</th>
				<th>Client Name</th>
				<th>Age</th>
				<th>Gender</th>
				<th>Occupation</th>
				<th>Occupation Other</th>
				<th>Sub County</th>
				<th>Test Location</th>
				<th>First Positive Sub County</th>
				<th>First Positive Hf</th>
				<th>First Positive Ward</th>
				<th>Confirmation County</th>
				<th>Confirmation Hf</th>
				<th>Referred County</th>
				<th>Referred Hf</th>
				<th>Referred Expected Linkage Date</th>
				<th>First Positive County Fac</th>
				<th>First Positive County Comm</th>
				<th>First Positive Date</th>
				<th>Confirmation Date</th>
				<th>First Positive Hf Mfl</th>
				<th>Confirmation Hf Mfl</th>
				<th>Referred Hf Mfl</th>
				<th>Follow Up 1</th>
				<th>Follow Up 1 Date</th>
				<th>Follow Up 1 Reached</th>
				<th>Follow Up 1 Fail Reason</th>
				<th>Follow Up 1 Fail Reason Other</th>
				<th>Follow Up 2</th>
				<th>Follow Up 2 Date</th>
				<th>Follow Up 2 Reached</th>
				<th>Follow Up 2 Fail Reason</th>
				<th>Follow Up 2 Fail Reason Other</th>
				<th>Linked</th>
				<th>Linked Date</th>
				<th>Linked County</th>
				<th>Linked Hf</th>
				<th>CCC Number</th>
				<th>Linked Comments</th>
				<th>Linked Reason</th>
				<th>Linked Reason Other</th>
				<th>Linked Hf Mfl</th>
			</thead>
			<tbody>
				<?php foreach ($records as $record):?>
			    	<tr>
			    		<td><?=$record->unique_key?></td>
						<td><?=$record->rec_status?></td>
						<td><?=$record->global_record_id?></td>
						<td><?=$record->first_save_logon_name?></td>
						<td><?=$record->first_save_time?></td>
						<td><?=$record->last_save_logon_name?></td>
						<td><?=$record->last_save_time?></td>
						<td><?=$record->partner?></td>
						<td><?=$record->county?></td>
						<td><?=$record->client_name?></td>
						<td><?=$record->age?></td>
						<td><?=$record->gender?></td>
						<td><?=$record->occupation?></td>
						<td><?=$record->occupation_other?></td>
						<td><?=$record->sub_county?></td>
						<td><?=$record->test_location?></td>
						<td><?=$record->first_positive_sub_county?></td>
						<td><?=$record->first_positive_hf?></td>
						<td><?=$record->first_positive_ward?></td>
						<td><?=$record->confirmation_county?></td>
						<td><?=$record->confirmation_hf?></td>
						<td><?=$record->referred_county?></td>
						<td><?=$record->referred_hf?></td>
						<td><?=$record->referred_expected_linkage_date?></td>
						<td><?=$record->first_positive_county_fac?></td>
						<td><?=$record->first_positive_county_comm?></td>
						<td><?=$record->first_positive_date?></td>
						<td><?=$record->confirmation_date?></td>
						<td><?=$record->first_positive_hf_mfl?></td>
						<td><?=$record->confirmation_hf_mfl?></td>
						<td><?=$record->referred_hf_mfl?></td>
						<td><?=$record->follow_up_1?></td>
						<td><?=$record->follow_up_1_date?></td>
						<td><?=$record->follow_up_1_reached?></td>
						<td><?=$record->follow_up_1_fail_reason?></td>
						<td><?=$record->follow_up_1_fail_reason_other?></td>
						<td><?=$record->follow_up_2?></td>
						<td><?=$record->follow_up_2_date?></td>
						<td><?=$record->follow_up_2_reached?></td>
						<td><?=$record->follow_up_2_fail_reason?></td>
						<td><?=$record->follow_up_2_fail_reason_other?></td>
						<td><?=$record->linked?></td>
						<td><?=$record->linked_date?></td>
						<td><?=$record->linked_county?></td>
						<td><?=$record->linked_hf?></td>
						<td><?=$record->ccc_number?></td>
						<td><?=$record->linked_comments?></td>
						<td><?=$record->linked_reason?></td>
						<td><?=$record->linked_reason_other?></td>
						<td><?=$record->linked_hf_mfl?></td>
			    	</tr>
			    <?php endforeach;?>
			</tbody>
		</table>
	</div>

	
<?php $this->view('footer'); ?>
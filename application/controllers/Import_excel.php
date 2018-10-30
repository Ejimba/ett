<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Import_excel extends CI_Controller {

	public function index()
	{
		$this->load->view('import_excel', ['error' => ' ' ]);
	}

	public function store()
	{
		$config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv'; // only allow csv
        $config['max_size'] = 30000; // 30mb
        $config['file_name'] = date('YmdHis').'_'.random_int(10000, 99999); // append some 5 digits from the gods 

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('etv1'))
        {
        	$this->session->set_flashdata('flash_message', $this->upload->display_errors());
        	redirect(base_url('import-excel'));
        }
        else
        {
        	$upload_data = $this->upload->data();
        	$data = ['upload_data' => $upload_data];
        	$etv1 = $upload_data['full_path'];

        	try {
        		$pdo_db = new \PDO("sqlite:".APPPATH.'database/ett.db');
        		$this->load->library('csvreader');
        		$records = $this->csvreader->parse_file($etv1);
				
				if($records) {

					$this->load->library('uuid');

					foreach ($records as $record) {
						$sql1_db = "
							INSERT INTO version_one(
								unique_key,
								rec_status,
								global_record_id,
								first_save_logon_name,
								first_save_time,
								last_save_logon_name,
								last_save_time,

								partner,
								county,
								client_name,
								age,
								gender,
								occupation,
								occupation_other,
								sub_county,

								test_location,
								first_positive_sub_county,
								first_positive_hf,
								first_positive_ward,
								confirmation_county,
								confirmation_hf,
								referred_county,
								referred_hf,
								referred_expected_linkage_date,
								first_positive_county_fac,
								first_positive_county_comm,
								first_positive_date,
								confirmation_date,
								first_positive_hf_mfl,
								confirmation_hf_mfl,
								referred_hf_mfl,

								follow_up_1,
								follow_up_1_date,
								follow_up_1_reached,
								follow_up_1_fail_reason,
								follow_up_1_fail_reason_other,
								follow_up_2,
								follow_up_2_date,
								follow_up_2_reached,
								follow_up_2_fail_reason,
								follow_up_2_fail_reason_other,

								linked,
								linked_date,
								linked_county,
								linked_hf,
								ccc_number,
								linked_comments,
								linked_reason,
								linked_reason_other,
								linked_hf_mfl
							) VALUES(
								?,?,?,?,?,?,?,?,?,?,
								?,?,?,?,?,?,?,?,?,?,
								?,?,?,?,?,?,?,?,?,?,
								?,?,?,?,?,?,?,?,?,?,
								?,?,?,?,?,?,?,?,?,?
						)";

						$current_user = trim(getenv('USERDOMAIN').'\\'.getenv('USERNAME'), '$');

						$unique_key = isset($record['unique_key']) ? $record['unique_key']:null;
						$rec_status = isset($record['rec_status']) ? $record['rec_status']:1;
						$global_record_id = isset($record['global_record_id']) ? $record['global_record_id']:$this->uuid->v4();
						$first_save_logon_name = isset($record['first_save_logon_name']) ? $record['first_save_logon_name']:$current_user;
						$first_save_time = isset($record['first_save_time']) ? $record['first_save_time']:date('Y-m-d H:i:s');
						$last_save_logon_name = isset($record['last_save_logon_name']) ? $record['last_save_logon_name']:$current_user;
						$last_save_time = isset($record['last_save_time']) ? $record['last_save_time']:date('Y-m-d H:i:s');

						$partner = isset($record['partner']) ? $record['partner']:null;
						$county = isset($record['county']) ? $record['county']:null;
						$sub_county = isset($record['sub_county']) ? strtoupper($record['sub_county']):null;
						$client_name = isset($record['client_name']) ? strtoupper($record['client_name']):null;
						$age = isset($record['age']) ? $record['age']:null;
						switch ($record['gender']) {
							case '1':
								$gender = 'Male';
								break;
							case '2':
								$gender = 'Female';
								break;
							default:
								$gender = null;
								break;
						}
						switch ($record['occupation']) {
						case '1':
							$occupation = 'Administration/Security';
							break;
						case '2':
							$occupation = 'Business person';
							break;
						case '3':
							$occupation = 'Casual/Self Employment';
							break;
						case '4':
							$occupation = 'Construction/Mining/Quarry';
							break;
						case '5':
							$occupation = 'Farmer';
							break;
						case '6':
							$occupation = 'Fisher Folk/Fishing Community';
							break;
						case '7':
							$occupation = 'Formal Employment';
							break;
						case '8':
							$occupation = 'Health Care Worker';
							break;
						case '9':
							$occupation = 'Hospitality';
							break;
						case '10':
							$occupation = 'House support staff';
							break;
						case '11':
							$occupation = 'House Wife';
							break;
						case '12':
							$occupation = 'Key Population';
							break;
						case '13':
							$occupation = 'None';
							break;
						case '14':
							$occupation = 'Other';
							break;
						case '15':
							$occupation = 'Student';
							break;
						case '16':
							$occupation = 'Transport Industry';
							break;
						case '17':
							$occupation = 'Missing Information';
							break;
						default:
							$occupation = null;
							break;
						}
						$occupation_other = isset($record['occupation_other']) ? $record['occupation_other']:null;
						switch ($record['test_location']) {
							case '1':
								$test_location = 'Facility';
								break;
							case '2':
								$test_location = 'Community';
								break;
							case '3':
								$test_location = 'Mobile Outreach';
								break;
							default:
								$test_location = null;
								break;
						}

						$first_positive_date = isset($record['first_positive_date']) ? $record['first_positive_date']:null;
						$first_positive_county_fac = isset($record['first_positive_county_fac']) ? $record['first_positive_county_fac']:null;
						$first_positive_hf = isset($record['first_positive_hf']) ? strtoupper($record['first_positive_hf']):null;
						$first_positive_hf_mfl = isset($record['first_positive_hf_mfl']) ? $record['first_positive_hf_mfl']:null;
						$first_positive_county_comm = isset($record['first_positive_county_comm']) ? $record['first_positive_county_comm']:null;
						$first_positive_sub_county = isset($record['first_positive_sub_county']) ? strtoupper($record['first_positive_sub_county']):null;
						$first_positive_ward = isset($record['first_positive_ward']) ? $record['first_positive_ward']:null;

						$referred_county = isset($record['referred_county']) ? strtoupper($record['referred_county']):null;
						$referred_hf = isset($record['referred_hf']) ? strtoupper($record['referred_hf']):null;
						$referred_hf_mfl = isset($record['referred_hf_mfl']) ? $record['referred_hf_mfl']:null;
						$referred_expected_linkage_date = isset($record['referred_expected_linkage_date']) ? $record['referred_expected_linkage_date']:null;
						
						switch ($record['follow_up_1']) {
							case '1':
								$follow_up_1 = 'Yes';
								break;
							default:
								$follow_up_1 = 'No';
								break;
						}
						$follow_up_1_date = isset($record['follow_up_1_date']) ? $record['follow_up_1_date']:null;
						switch ($record['follow_up_1_reached']) {
							case '1':
								$follow_up_1_reached = 'Yes';
								break;
							case '2':
								$follow_up_1_reached = 'No';
								break;
							default:
								$follow_up_1_reached = null;
								break;
						}
						switch ($record['follow_up_1_fail_reason']) {
							case '1':
								$follow_up_1_fail_reason = 'Dead before linkage';
								break;
							case '2':
								$follow_up_1_fail_reason = 'Declined';
								break;
							case '3':
								$follow_up_1_fail_reason = 'Denial';
								break;
							case '4':
								$follow_up_1_fail_reason = 'Incomplete Records';
								break;
							case '5':
								$follow_up_1_fail_reason = 'Mteja';
								break;
							case '6':
								$follow_up_1_fail_reason = 'No locator information';
								break;
							case '7':
								$follow_up_1_fail_reason = 'Not found/available at home';
								break;
							case '8':
								$follow_up_1_fail_reason = 'Not Reachable';
								break;
							case '9':
								$follow_up_1_fail_reason = 'Other';
								break;
							case '10':
								$follow_up_1_fail_reason = 'Relocated';
								break;
							case '11':
								$follow_up_1_fail_reason = 'Undecided/requires more time to initiate ART';
								break;
							case '12':
								$follow_up_1_fail_reason = 'Verbal report of linkage';
								break;
							case '13':
								$follow_up_1_fail_reason = 'Wrong Locator';
								break;
							default:
								$follow_up_1_fail_reason = null;
								break;
						}
						$follow_up_1_fail_reason_other = isset($record['follow_up_1_fail_reason_other']) ? $record['follow_up_1_fail_reason_other']:null;

						switch ($record['follow_up_2']) {
							case '1':
								$follow_up_2 = 'Yes';
								break;
							case '2':
								$follow_up_2 = 'No';
								break;
							default:
								$follow_up_2 = null;
								break;
						}
						$follow_up_2_date = isset($record['follow_up_2_date']) ? $record['follow_up_2_date']:null;
						switch ($record['follow_up_2_reached']) {
							case '1':
								$follow_up_2_reached = 'Yes';
								break;
							case '2':
								$follow_up_2_reached = 'No';
								break;
							default:
								$follow_up_2_reached = null;
								break;
						}
						switch ($record['follow_up_2_fail_reason']) {
							case '1':
								$follow_up_2_fail_reason = 'Dead before linkage';
								break;
							case '2':
								$follow_up_2_fail_reason = 'Declined';
								break;
							case '3':
								$follow_up_2_fail_reason = 'Denial';
								break;
							case '4':
								$follow_up_2_fail_reason = 'Incomplete Records';
								break;
							case '5':
								$follow_up_2_fail_reason = 'Mteja';
								break;
							case '6':
								$follow_up_2_fail_reason = 'No locator information';
								break;
							case '7':
								$follow_up_2_fail_reason = 'Not found/available at home';
								break;
							case '8':
								$follow_up_2_fail_reason = 'Not Reachable';
								break;
							case '9':
								$follow_up_2_fail_reason = 'Other';
								break;
							case '10':
								$follow_up_2_fail_reason = 'Relocated';
								break;
							case '11':
								$follow_up_2_fail_reason = 'Undecided/requires more time to initiate ART';
								break;
							case '12':
								$follow_up_2_fail_reason = 'Verbal report of linkage';
								break;
							case '13':
								$follow_up_2_fail_reason = 'Wrong Locator';
								break;
							default:
								$follow_up_2_fail_reason = null;
								break;
						}
						$follow_up_2_fail_reason_other = isset($record['follow_up_2_fail_reason_other']) ? $record['follow_up_2_fail_reason_other']:null;
						
						switch ($record['linked']) {
							case '1':
								$linked = 'Yes';
								break;
							case '2':
								$linked = 'No';
								break;
							default:
								$linked = null;
								break;
						}
						$confirmation_date = isset($record['confirmation_date']) ? $record['confirmation_date']:null;
						$linked_date = isset($record['linked_date']) ? $record['linked_date']:null;
						$linked_county = isset($record['linked_county']) ? $record['linked_county']:null;
						$linked_hf = isset($record['linked_hf']) ? $record['linked_hf']:null;
						$linked_hf_mfl = isset($record['linked_hf_mfl']) ? $record['linked_hf_mfl']:null;
						$ccc_number = isset($record['ccc_number']) ? $record['ccc_number']:null;

						switch ($record['linked_reason']) {
							case '1':
								$linked_reason = 'Dead before linkage';
								break;
							case '2':
								$linked_reason = 'Declined';
								break;
							case '3':
								$linked_reason = 'Denial';
								break;
							case '4':
								$linked_reason = 'Incomplete Records';
								break;
							case '5':
								$linked_reason = 'Mteja';
								break;
							case '6':
								$linked_reason = 'No locator information';
								break;
							case '7':
								$linked_reason = 'Not found/available at home';
								break;
							case '8':
								$linked_reason = 'Not Reachable';
								break;
							case '9':
								$linked_reason = 'Other';
								break;
							case '10':
								$linked_reason = 'Relocated';
								break;
							case '11':
								$linked_reason = 'Undecided/requires more time to initiate ART';
								break;
							case '12':
								$linked_reason = 'Verbal report of linkage';
								break;
							case '13':
								$linked_reason = 'Wrong Locator';
								break;
							default:
								$linked_reason = null;
								break;
						}
						$linked_reason_other = isset($record['linked_reason_other']) ? $record['linked_reason_other']:null;
						$linked_comments = isset($record['linked_comments']) ? $record['linked_comments']:null;



						$confirmation_county = isset($record['confirmation_county']) ? $record['confirmation_county']:null;
						$confirmation_hf = isset($record['confirmation_hf']) ? $record['confirmation_hf']:null;
						$confirmation_hf_mfl = isset($record['confirmation_hf_mfl']) ? $record['confirmation_hf_mfl']:null;



						$q1_db = $pdo_db->prepare($sql1_db);
						$q1_db->execute([
							
							$unique_key,
							$rec_status,
							$global_record_id,
							$first_save_logon_name,
							$first_save_time,
							$last_save_logon_name,
							$last_save_time,

							$partner,
							$county,
							$client_name,
							$age,
							$gender,
							$occupation,
							$occupation_other,
							$sub_county,

							$test_location,
							$first_positive_sub_county,
							$first_positive_hf,
							$first_positive_ward,
							$confirmation_county,
							$confirmation_hf,
							$referred_county,
							$referred_hf,
							$referred_expected_linkage_date,
							$first_positive_county_fac,
							$first_positive_county_comm,
							$first_positive_date,
							$confirmation_date,
							$first_positive_hf_mfl,
							$confirmation_hf_mfl,
							$referred_hf_mfl,

							$follow_up_1,
							$follow_up_1_date,
							$follow_up_1_reached,
							$follow_up_1_fail_reason,
							$follow_up_1_fail_reason_other,
							$follow_up_2,
							$follow_up_2_date,
							$follow_up_2_reached,
							$follow_up_2_fail_reason,
							$follow_up_2_fail_reason_other,

							$linked,
							$linked_date,
							$linked_county,
							$linked_hf,
							$ccc_number,
							$linked_comments,
							$linked_reason,
							$linked_reason_other,
							$linked_hf_mfl
						]);
					}
				} else {
					throw new Exception("Error while processing request", 1);
				}

				$this->session->set_flashdata('flash_message', 'Import processed successfully');
        		redirect(base_url('import-excel'));
				
        	} catch(Exception $e) {
        		$this->session->set_flashdata('flash_message', $e->getMessage());
        		redirect(base_url('import-excel'));
        	}
        }
	}
}

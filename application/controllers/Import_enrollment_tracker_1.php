<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Import_enrollment_tracker_1 extends CI_Controller {

	public function index()
	{
		$this->load->view('import_enrollment_tracker_1');
	}

	public function store()
	{
		$config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'mdb'; // only allow access db
        $config['max_size'] = 30000; // 30mb
        $config['file_name'] = date('YmdHis').'_'.random_int(10000, 99999); // append some 5 digits from the gods 

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('etv1'))
        {
        	$this->session->set_flashdata('flash_message', $this->upload->display_errors()); 
        	redirect(base_url('import-enrollment-tracker-1'));
        }
        else
        {
        	$upload_data = $this->upload->data();
        	$data = ['upload_data' => $upload_data];

        	$etv1 = $upload_data['full_path'];

        	try {
        		$driver = '{Microsoft Access Driver (*.mdb)}'; // will work in windows os only. i adore linux but sorry guyz
        		// Linux : look for something in regards to MDBTools, not sure of write capabilities though
        		$mdb_file = $etv1;
        		$pdo_etv1 = new \PDO("odbc:Driver=$driver;DBQ=$mdb_file;Uid=Admin;");
        		$pdo_db = new \PDO("sqlite:".APPPATH.'database/ett.db');
				
				// meta data records
				$sql1_etv1 = "
					SELECT * FROM LinkageTracker
					JOIN LinkageTracker3 ON LinkageTracker3.GlobalRecordId = LinkageTracker.GlobalRecordId
					JOIN LinkageTracker4 ON LinkageTracker4.GlobalRecordId = LinkageTracker.GlobalRecordId
					JOIN LinkageTracker5 ON LinkageTracker5.GlobalRecordId = LinkageTracker.GlobalRecordId
					JOIN LinkageTracker6 ON LinkageTracker6.GlobalRecordId = LinkageTracker.GlobalRecordId
				";
				$sql1_etv1 = "
					SELECT * FROM LinkageTracker, LinkageTracker3, LinkageTracker4, LinkageTracker5,LinkageTracker6
					WHERE
					LinkageTracker3.GlobalRecordId = LinkageTracker.GlobalRecordId AND
					LinkageTracker4.GlobalRecordId = LinkageTracker.GlobalRecordId AND
					LinkageTracker5.GlobalRecordId = LinkageTracker.GlobalRecordId AND
					LinkageTracker6.GlobalRecordId = LinkageTracker.GlobalRecordId
				";
				$q1_etv1 = $pdo_etv1->prepare($sql1_etv1);

				if($q1_etv1->execute()) {
					while ($record = $q1_etv1->fetch(\PDO::FETCH_OBJ)) {
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

						$q1_db = $pdo_db->prepare($sql1_db);
						$q1_db->execute([
							
							$record->UniqueKey,
							$record->RECSTATUS,
							$record->GlobalRecordId,
							$record->FirstSaveLogonName,
							$record->FirstSaveTime,
							$record->LastSaveLogonName,
							$record->LastSaveTime,

							$record->partner,
							$record->county,
							$record->client_name,
							$record->age,
							$record->gender,
							$record->occupation,
							$record->occupation_other,
							$record->sub_county,

							$record->test_location,
							$record->first_positive_sub_county,
							$record->first_positive_hf,
							$record->first_positive_ward,
							$record->confirmation_county,
							$record->confirmation_hf,
							$record->referred_county,
							$record->referred_hf,
							$record->referred_expected_linkage_date,
							$record->first_positive_county_fac,
							$record->first_positive_county_comm,
							$record->first_positive_date,
							$record->confirmation_date,
							$record->first_positive_hf_mfl,
							$record->confirmation_hf_mfl,
							$record->referred_hf_mfl,

							$record->follow_up_1,
							$record->follow_up_1_date,
							$record->follow_up_1_reached,
							$record->follow_up_1_fail_reason,
							$record->follow_up_1_fail_reason_other,
							$record->follow_up_2,
							$record->follow_up_2_date,
							$record->follow_up_2_reached,
							$record->follow_up_2_fail_reason,
							$record->follow_up_2_fail_reason_other,

							$record->linked,
							$record->linked_date,
							$record->linked_county,
							$record->linked_hf,
							$record->ccc_number,
							$record->linked_comments,
							$record->linked_reason,
							$record->linked_reason_other,
							$record->linked_hf_mfl
						]);
					}
				} else {
					throw new Exception("Error while processing request", 1);
				}

				$this->session->set_flashdata('flash_message', 'Import processed successfully');
        		redirect(base_url('import-enrollment-tracker-1'));
				
        	} catch(Exception $e) {
        		$this->session->set_flashdata('flash_message', $e->getMessage());
        		redirect(base_url('import-enrollment-tracker-1'));
        	}
        }
	}
}

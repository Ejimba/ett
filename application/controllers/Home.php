<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
    	$this->load->helper(array('form', 'url'));
    }

	public function index()
	{
		$this->load->view('home', ['error' => ' ' ]);
	}

	public function store()
	{
		$config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'mdb'; // only allow access db
        $config['max_size'] = 10000; // 10mb
        $config['file_name'] = date('YmdHis').'_'.random_int(10000, 99999); // append some 5 digits from the gods 

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('etv1'))
        {
        	$error = ['error' => $this->upload->display_errors()];
        	$this->load->view('home', $error);
        }
        else
        {
        	$upload_data = $this->upload->data();
        	$data = ['upload_data' => $upload_data];

        	$etv1 = $upload_data['full_path'];
        	$etv2 = $upload_data['file_path'].$upload_data['raw_name'].'_v2'.$upload_data['file_ext'];
        	$zipv2 = $upload_data['file_path'].$upload_data['raw_name'].'_v2.zip';
        	
        	copy(APPPATH.'database/EnrollmentTracker2.mdb', $etv2);

        	try {
        		$driver = '{Microsoft Access Driver (*.mdb)}'; // will work in windows os only. i adore linux but sorry guyz
        		// Linux : look for something in regards to MDBTools, not sure of write capabilities though
        		$mdb_file = $etv1;
        		$pdo_etv1 = new \PDO("odbc:Driver=$driver;DBQ=$mdb_file;Uid=Admin;");
        		$mdb_file = $etv2;
        		$pdo_etv2 = new \PDO("odbc:Driver=$driver;DBQ=$mdb_file;Uid=Admin;");
				
				// meta data records
				$sql1_etv1 = "SELECT * FROM LinkageTracker";
				$q1_etv1 = $pdo_etv1->prepare($sql1_etv1);

				if($q1_etv1->execute()) {
					// we do single record fetch/insert combo as opposed to bulk insert. this is ms access, i don't wanna
					// test it's patience
					while ($record = $q1_etv1->fetch(\PDO::FETCH_OBJ)) {
						$sql1_etv2 = "INSERT INTO EnrollmentTracker(RECSTATUS, GlobalRecordId, FirstSaveLogonName, FirstSaveTime, LastSaveLogonName, LastSaveTime) VALUES(?,?,?,?,?,?)";
						$q1_etv2 = $pdo_etv2->prepare($sql1_etv2);
						$q1_etv2->execute([
							$record->RECSTATUS,
							$record->GlobalRecordId,
							$record->FirstSaveLogonName,
							$record->FirstSaveTime,
							$record->LastSaveLogonName,
							$record->LastSaveTime,
						]);
					}
				} else {
					throw new Exception("Error while processing request", 1);
				}

				// testing records
				$sql2_etv1 = "SELECT * FROM LinkageTracker3 LEFT JOIN LinkageTracker4 ON LinkageTracker4.GlobalRecordId = LinkageTracker3.GlobalRecordId";
				$q2_etv1 = $pdo_etv1->prepare($sql2_etv1);

				if($q2_etv1->execute()) {
					// we do single record fetch/insert combo as opposed to bulk insert. this is ms access, i don't wanna
					// test it's patience
					while ($testing = $q2_etv1->fetch(\PDO::FETCH_OBJ)) {
						$sql2_etv2 = "INSERT INTO EnrollmentTracker1(GlobalRecordId, search_by, search_db, partner, hts_no, client_name, age, gender, occupation, occupation_other, setting, initial_test_county, initial_test_sub_county, initial_test_hf_mfl, initial_test_hf, initial_test_date, initial_test_ward, enrolled_on_initial_test, referred_country, referred_initial_test_hf, referred_outside_kenya, referred_country_other, referred_county, preferred_enrollment_date, referred_sub_county, referred_hf, referred_hf_mfl, hts_no_2, client_name_2, ccc_no_2) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						$q2_etv2 = $pdo_etv2->prepare($sql2_etv2);
						$q2_etv2->execute([
							$testing->GlobalRecordId,
							null,
							null,
							$testing->partner,
							null,
							$testing->client_name,
							$testing->age,
							$testing->gender,
							$testing->occupation,
							$testing->occupation_other,
							$testing->test_location,
							$testing->first_positive_county_fac ? $testing->first_positive_county_fac:$testing->first_positive_county_comm,
							$testing->first_positive_sub_county,
							$testing->first_positive_hf_mfl,
							$testing->first_positive_hf,
							$testing->first_positive_date,
							$testing->first_positive_ward,
							null,
							null,
							null,
							null,
							null,
							$testing->referred_county,
							$testing->referred_expected_linkage_date,
							null, // $testing->referred_sub_county,
							$testing->referred_hf,
							$testing->referred_hf_mfl,
							null,
							null,
							null
						]);
					}
				} else {
					throw new Exception("Error while processing request", 1);
				}

				// now the enrollment records
				$sql3_etv1 = "SELECT * FROM LinkageTracker6";
				$q3_etv1 = $pdo_etv1->prepare($sql3_etv1);

				if($q3_etv1->execute()) {
					while ($enrollment = $q3_etv1->fetch(\PDO::FETCH_OBJ)) {
						$sql3_etv2 = "INSERT INTO EnrollmentTracker2(GlobalRecordId, has_ccc_no, enrolled_initial_test_hf, enrolled_referred_hf, enrolled_outside_kenya, ccc_no, enrolled_date, enrolled_county, enrolled_sub_county, enrolled_hf, enrolled_hf_mfl, enrolled_country, enrolled_country_other, enrolled_reason_unsuccessful, enrolled_reason_unsuccessful_other, enrolled_remarks, tracing) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						$q3_etv2 = $pdo_etv2->prepare($sql3_etv2);
						$q3_etv2->execute([
							$enrollment->GlobalRecordId,
							null,
							null,
							null,
							null,
							$enrollment->ccc_number,
							$enrollment->linked_date,
							$enrollment->linked_county,
							null,
							$enrollment->linked_hf,
							$enrollment->linked_hf_mfl,
							null,
							null,
							$enrollment->linked_reason,
							$enrollment->linked_reason_other,
							null,
							null
						]);
					}
				} else {
					throw new Exception("Error while processing request", 1);
				}

				$this->load->library('zip');
				$this->zip->compression_level = 9;
				$this->zip->read_file(APPPATH.'database/EnrollmentTracker2.prj', 'EnrollmentTracker2/EnrollmentTracker2.prj');
				$this->zip->read_file($etv2, 'EnrollmentTracker2/EnrollmentTracker2.mdb');
				$this->zip->archive($zipv2);
				$this->zip->download('EnrollmentTracker2.zip');
				
        	} catch(Exception $e) {
        		$error = ['error' => $e->getMessage()];
        		$this->load->view('home', $error);
        	}
        }
	}
}

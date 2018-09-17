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
				$sql2_etv1 = "SELECT * FROM LinkageTracker3";
				$q2_etv1 = $pdo_etv1->prepare($sql2_etv1);

				if($q2_etv1->execute()) {
					// we do single record fetch/insert combo as opposed to bulk insert. this is ms access, i don't wanna
					// test it's patience
					while ($testing = $q2_etv1->fetch(\PDO::FETCH_OBJ)) {
						$sql2_etv2 = "INSERT INTO EnrollmentTracker1(GlobalRecordId, partner, client_name, age, gender, occupation, occupation_other) VALUES(?,?,?,?,?,?,?)";
						$q2_etv2 = $pdo_etv2->prepare($sql2_etv2);
						$q2_etv2->execute([
							$testing->GlobalRecordId,
							$testing->partner,
							$testing->client_name,
							$testing->age,
							$testing->gender,
							$testing->occupation,
							$testing->occupation_other,
						]);
					}
				} else {
					throw new Exception("Error while processing request", 1);
				}

				// now the enrollment records

				echo "Done";

        	} catch(Exception $e) {
        		$error = ['error' => $e->getMessage()];
        		$this->load->view('home', $error);
        	}
        }
	}
}

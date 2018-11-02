<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller {

	public function index()
	{
		$this->load->view('export', ['error' => ' ' ]);
	}

	public function store()
	{
        $file_name = APPPATH.'../uploads/generated_'.date('YmdHis').'_'.random_int(10000, 99999);
        $etv2 = $file_name.'_v2.mdb';
        $zipv2 = $file_name.'_v2.zip';
        
        copy(APPPATH.'database/EnrollmentTracker2.mdb', $etv2);

    	try {
    		$driver = '{Microsoft Access Driver (*.mdb)}'; // will work in windows os only. i adore linux but sorry guyz
    		// Linux : look for something in regards to MDBTools, not sure of write capabilities though
    		$mdb_file = $etv2;

    		$pdo_db = new \PDO("sqlite:".APPPATH.'database/ett.db');
			$sql1_db = "SELECT * FROM version_one";
			$q1_db = $pdo_db->prepare($sql1_db);

			if($q1_db->execute()) {
				$this->load->library('uuid');
				$pdo_etv2 = new \PDO("odbc:Driver=$driver;DBQ=$mdb_file;Uid=Admin;");
				// we do single record fetch/insert combo as opposed to bulk insert. this is ms access, i don't wanna
				// test it's patience
				while ($record = $q1_db->fetch(\PDO::FETCH_OBJ)) {

					$unique_key = $record->unique_key;
					$rec_status = $record->rec_status;
					$global_record_id = $record->global_record_id;
					$first_save_logon_name = $record->first_save_logon_name;
					$first_save_time = $record->first_save_time;
					$last_save_logon_name = $record->last_save_logon_name;
					$last_save_time = $record->last_save_time;

					switch ($record->partner) {
						case 'CHS':
							$partner = 'CHS Naishi';
							$partner = 'CHS Shinda';
							$partner = 'CHS Tegemeza Plus';
							// to establish which one began earlier
							$partner = 'CHS Naishi';
							break;
						case 'EGPAF':
							$partner = 'EGPAF Timiza';
							break;
						case 'ICAP':
							$partner = 'ICAP'; // removed ?
							break;
						case 'IRDO':
							$partner = 'IRDO Tuungane 3';
							break;
						case 'KCCB':
							$partner = 'KCCB KARP';
							break;
						case 'LVCT':
							$partner = 'LVCT Daraja';
							$partner = 'LVCT Steps';
							// to establish which one began earlier
							$partner = 'LVCT Daraja';
							break;
						case 'Ngima for SURE':
							$partner = 'Ngima for Sure';
							break;
						case 'UCSF':
							$partner = 'UCSF Clinical Kisumu';
							break;
						case 'UMB':
							$partner = 'UMB PACT Kamili';
							$partner = 'UMB Timiza';
							// to establish which one began earlier
							$partner = 'UMB PACT Kamili';
							break;
						default:
							// $partner = 'AMREF Nairobi Kitui';
							// $partner = 'Bomu Hospital Affiliated Sites';
							// $partner = 'CHAK CHAP Uzima';
							// $partner = 'Columbia STARS';
							// $partner = 'Coptic Hospitals';
							// $partner = 'Faith-Based Sites in the Eastern Slums of Nairobi';
							// $partner = 'HWWK Nairobi Eastern';
							// $partner = 'Kenya Disciplined Services ZUIA';
							// $partner = 'Kenya Prison Services';
							// $partner = 'Partnership with MOH on HIV/AIDS and TB Programs';
							// $partner = 'UON COE Niche';
							// $partner = 'UON CRISSP Plus';

							// when no partner matches, just put previous value

							$partner = $record->partner;
							break;
					}
					$county = $record->county;
					$client_name = $record->client_name;
					$age = $record->age;
					$gender = $record->gender;
					switch ($record->occupation) {
						case 'Administration/Security':
							$occupation = 'Professional/salaried worker (e.g. teacher, security, police, nurse, administrator)';
							break;
						case 'Business person':
							$occupation = 'Business owner (e.g. duka, kiosk, jua kali ...)';
							break;
						case 'Casual/Self Employment':
							$occupation = 'Unskilled labor (e.g. casual, shamba, construction, miner, quarry)';
							break;
						case 'Construction/Mining/Quarry':
							$occupation = 'Unskilled labor (e.g. casual, shamba, construction, miner, quarry)';
							break;
						case 'Farmer':
							$occupation = 'Farmer / agricultural work';
							break;
						case 'Fisher Folk/Fishing Community':
							$occupation = 'Fisherman/fisherwoman/fisher folk-related occupation';
							break;
						case 'Formal Employment':
							$occupation = 'Professional/salaried worker (e.g. teacher, security, police, nurse, administrator)';
							break;
						case 'Health Care Worker':
							$occupation = 'Professional/salaried worker (e.g. teacher, security, police, nurse, administrator)';
							break;
						case 'Hospitality':
							$occupation = 'Professional/salaried worker (e.g. teacher, security, police, nurse, administrator)';
							break;
						case 'House support staff':
							$occupation = 'Homemaker/House wife';
							break;
						case 'House Wife':
							$occupation = 'Homemaker/House wife';
							break;
						case 'Key Population':
							$occupation = 'Sex worker';
							break;
						case 'None':
							$occupation = 'Unemployed';
							break;
						case 'Other':
							$occupation = 'Other';
							break;
						case 'Student':
							$occupation = 'Student (primary, secondary, tertiary)';
							break;
						case 'Transport Industry':
							$occupation = 'Transportation (e.g. bodaboda/tuk tuk/matatu or bus crew)';
							break;
						case 'Missing Information':
							$occupation = 'Other';
							break;
						default:
							// Waiter/waitress (in hotel/bar/night club) no category ?
							$occupation = null;
							break;
					}
					$occupation_other = $record->occupation_other;
					$sub_county = trim($record->sub_county);
					
					switch ($record->test_location) {
						case 'Facility':
							$test_location = 'Facility';
							break;
						case 'Community':
							$test_location = 'Community';
							break;
						case 'Mobile Outreach':
							$test_location = 'Community';
							break;
						default:
							$test_location = null;
							break;
					}

					$first_positive_sub_county = trim($record->first_positive_sub_county);
					$first_positive_hf = $record->first_positive_hf;
					$first_positive_ward = $record->first_positive_ward;
					$confirmation_county = $record->confirmation_county;
					$confirmation_hf = $record->confirmation_hf;
					$referred_county = $record->referred_county;
					$referred_hf = $record->referred_hf;
					$referred_expected_linkage_date = $record->referred_expected_linkage_date;
					$first_positive_county_fac = $record->first_positive_county_fac;
					$first_positive_county_comm = $record->first_positive_county_comm;
					$first_positive_date = $record->first_positive_date;
					$confirmation_date = $record->confirmation_date;
					$first_positive_hf_mfl = $record->first_positive_hf_mfl;
					$confirmation_hf_mfl = $record->confirmation_hf_mfl;
					$referred_hf_mfl = $record->referred_hf_mfl;
					$follow_up_1 = $record->follow_up_1;
					$follow_up_1_date = $record->follow_up_1_date;
					$follow_up_1_reached = $record->follow_up_1_reached;
					switch ($record->follow_up_1_fail_reason) {
						case 'Dead before linkage':
							$follow_up_1_fail_reason = 'Died';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Declined':
							$follow_up_1_fail_reason = 'Other';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Denial':
							$follow_up_1_fail_reason = 'Other';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Incomplete Records':
							$follow_up_1_fail_reason = 'Other';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Mteja':
							$follow_up_1_fail_reason = 'Mteja, calls not going through, not picking calls';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'No locator information':
							$follow_up_1_fail_reason = 'No locator information in record';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Not found/available at home':
							$follow_up_1_fail_reason = 'Not found at home';
							$follow_up_1_method = 'Physical Tracing';
							break;
						case 'Not Reachable':
							$follow_up_1_fail_reason = 'Not found at home';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Other':
							$follow_up_1_fail_reason = 'Other';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Relocated':
							$follow_up_1_fail_reason = 'Migrated from reported location';
							$follow_up_1_method = 'Physical Tracing';
							break;
						case 'Undecided/requires more time to initiate ART':
							$follow_up_1_fail_reason = 'Other';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Verbal report of linkage':
							$follow_up_1_fail_reason = 'Other';
							$follow_up_1_method = 'Phone Tracing';
							break;
						case 'Wrong Locator':
							$follow_up_1_fail_reason = 'Incorrect locator information in record';
							$follow_up_1_method = 'Phone Tracing';
							break;
						default:
							$follow_up_1_fail_reason = null;
							$follow_up_1_method = 'Phone Tracing';
							break;
					}
					$follow_up_1_fail_reason_other = $record->follow_up_1_fail_reason_other;
					$follow_up_2 = $record->follow_up_2;
					$follow_up_2_date = $record->follow_up_2_date;
					$follow_up_2_reached = $record->follow_up_2_reached;
					switch ($record->follow_up_2_fail_reason) {
						case 'Dead before linkage':
							$follow_up_2_fail_reason = 'Died';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Declined':
							$follow_up_2_fail_reason = 'Other';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Denial':
							$follow_up_2_fail_reason = 'Other';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Incomplete Records':
							$follow_up_2_fail_reason = 'Other';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Mteja':
							$follow_up_2_fail_reason = 'Mteja, calls not going through, not picking calls';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'No locator information':
							$follow_up_2_fail_reason = 'No locator information in record';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Not found/available at home':
							$follow_up_2_fail_reason = 'Not found at home';
							$follow_up_2_method = 'Physical Tracing';
							break;
						case 'Not Reachable':
							$follow_up_2_fail_reason = 'Not found at home';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Other':
							$follow_up_2_fail_reason = 'Other';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Relocated':
							$follow_up_2_fail_reason = 'Migrated from reported location';
							$follow_up_2_method = 'Physical Tracing';
							break;
						case 'Undecided/requires more time to initiate ART':
							$follow_up_2_fail_reason = 'Other';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Verbal report of linkage':
							$follow_up_2_fail_reason = 'Other';
							$follow_up_2_method = 'Phone Tracing';
							break;
						case 'Wrong Locator':
							$follow_up_2_fail_reason = 'Incorrect locator information in record';
							$follow_up_2_method = 'Phone Tracing';
							break;
						default:
							$follow_up_2_fail_reason = null;
							$follow_up_2_method = 'Phone Tracing';
							break;
					}
					$follow_up_2_fail_reason_other = $record->follow_up_2_fail_reason_other;
					$linked = $record->linked;
					$linked_date = $record->linked_date;
					$linked_county = $record->linked_county;
					$linked_hf = $record->linked_hf;
					$ccc_number = $record->ccc_number;
					$linked_comments = $record->linked_comments;

					switch ($record->linked_reason) {
						case 'Dead before linkage':
							$linked_reason = 'Died';
							break;
						case 'Declined':
							$linked_reason = 'Declined (Refused) to start care and requested NO FURTHER FOLLOW-UP';
							break;
						case 'Denial':
							$linked_reason = 'Denies having HIV (Does not believe he/she has HIV, requested another test)';
							break;
						case 'Incomplete Records':
							$linked_reason = 'Other';
							break;
						case 'Mteja':
							$linked_reason = 'Other';
							break;
						case 'No locator information':
							$linked_reason = 'Other';
							break;
						case 'Not found/available at home':
							$linked_reason = 'Other';
							break;
						case 'Not Reachable':
							$linked_reason = 'Other';
							break;
						case 'Other':
							$linked_reason = 'Other';
							break;
						case 'Relocated':
							$linked_reason = 'Migrated from reported location';
							break;
						case 'Undecided/requires more time to initiate ART':
							$linked_reason = 'Undecided about starting care / requested more time to decide';
							break;
						case 'Verbal report of linkage':
							$linked_reason = 'Verbal report of linkage but CCC# not provided or not verifiable';
							break;
						case 'Wrong Locator':
							$linked_reason = 'Other';
							break;
						default:
							$linked_reason = null;
							break;
					}
					$linked_reason_other = $record->linked_reason_other;
					$linked_hf_mfl = $record->linked_hf_mfl;

					// variables not in tracker 1
					$hts_no = isset($record->hts_no) ? $record->hts_no:null;
					if($linked_hf_mfl == $first_positive_hf_mfl) {
						$enrolled_on_initial_test = -1;
					} else {
						$enrolled_on_initial_test = 0;
					}
					if($referred_hf_mfl == $first_positive_hf_mfl) {
						$referred_initial_test_hf = -1;
					} else {
						$referred_initial_test_hf = 0;
					}
					$referred_outside_kenya = 0;
					$referred_country = null;
					$referred_country_other = null;

					if($linked_hf_mfl == $first_positive_hf_mfl) {
						$enrolled_initial_test_hf = -1;
					} else {
						$enrolled_initial_test_hf = 0;
					}
					if($linked_hf_mfl == $referred_hf_mfl) {
						$enrolled_referred_hf = -1;
					} else {
						$enrolled_referred_hf = 0;
					}
					$enrolled_outside_kenya = 0;

					if($follow_up_1 == 'Yes' || $follow_up_2 == 'Yes') {
						$tracing = 'Yes';
					} else {
						$tracing = null;
					}

					$sql1_etv2 = "INSERT INTO EnrollmentTracker(RECSTATUS, GlobalRecordId, FirstSaveLogonName, FirstSaveTime, LastSaveLogonName, LastSaveTime) VALUES(?,?,?,?,?,?)";
					$q1_etv2 = $pdo_etv2->prepare($sql1_etv2);
					$q1_etv2->execute([
						$rec_status,
						$global_record_id,
						$first_save_logon_name,
						$first_save_time,
						$last_save_logon_name,
						$last_save_time,
					]);

					$sql2_etv2 = "INSERT INTO EnrollmentTracker1(GlobalRecordId, search_by, search_db, partner, hts_no, client_name, age, gender, occupation, occupation_other, setting, initial_test_county, initial_test_sub_county, initial_test_hf_mfl, initial_test_hf, initial_test_date, initial_test_ward, enrolled_on_initial_test, referred_country, referred_initial_test_hf, referred_outside_kenya, referred_country_other, referred_county, preferred_enrollment_date, referred_sub_county, referred_hf, referred_hf_mfl, hts_no_2, client_name_2, ccc_no_2) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					$q2_etv2 = $pdo_etv2->prepare($sql2_etv2);
					$q2_etv2->execute([
						$global_record_id,
						null,
						null,
						$partner,
						$hts_no,
						$client_name,
						$age,
						$gender,
						$occupation,
						$occupation_other,
						$test_location,
						$first_positive_county_fac ? $first_positive_county_fac:$first_positive_county_comm,
						$first_positive_sub_county,
						$first_positive_hf_mfl,
						$first_positive_hf,
						$first_positive_date,
						$first_positive_ward,
						$enrolled_on_initial_test,
						$referred_country,
						$referred_initial_test_hf,
						$referred_outside_kenya,
						$referred_country_other,
						$referred_county,
						$referred_expected_linkage_date,
						null, // $referred_sub_county,
						$referred_hf,
						$referred_hf_mfl,
						$hts_no,
						$client_name,
						$ccc_number
					]);

					$sql3_etv2 = "INSERT INTO EnrollmentTracker2(GlobalRecordId, has_ccc_no, enrolled_initial_test_hf, enrolled_referred_hf, enrolled_outside_kenya, ccc_no, enrolled_date, enrolled_county, enrolled_sub_county, enrolled_hf, enrolled_hf_mfl, enrolled_country, enrolled_country_other, enrolled_reason_unsuccessful, enrolled_reason_unsuccessful_other, enrolled_remarks, tracing) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					$q3_etv2 = $pdo_etv2->prepare($sql3_etv2);
					$q3_etv2->execute([
						$global_record_id,
						$ccc_number ? 'Yes':'No',
						$enrolled_initial_test_hf,
						$enrolled_referred_hf,
						$enrolled_outside_kenya,
						$ccc_number,
						$linked_date,
						$linked_county,
						null,
						$linked_hf,
						$linked_hf_mfl,
						null,
						null,
						$linked_reason,
						$linked_reason_other,
						$linked_comments,
						$tracing
					]);

					if($follow_up_1 == 'Yes') {

						$follow_up_1_global_record_id = $this->uuid->v4();

						$sql4_etv2 = "INSERT INTO Tracing(RECSTATUS, GlobalRecordId, FirstSaveLogonName, FirstSaveTime, LastSaveLogonName, LastSaveTime, FKEY) VALUES(?,?,?,?,?,?,?)";
						$q4_etv2 = $pdo_etv2->prepare($sql4_etv2);
						$q4_etv2->execute([
							$rec_status,
							$follow_up_1_global_record_id,
							$first_save_logon_name,
							$first_save_time,
							$last_save_logon_name,
							$last_save_time,
							$global_record_id,
						]);

						$sql5_etv2 = "INSERT INTO Tracing3(GlobalRecordId, tracing_date, tracing_method, tracing_outcome, tracing_unsuccessful_reason, tracing_unsuccessful_reason_other) VALUES(?,?,?,?,?,?)";
						$q5_etv2 = $pdo_etv2->prepare($sql5_etv2);
						$q5_etv2->execute([
							$follow_up_1_global_record_id,
							$follow_up_1_date,
							$follow_up_1_method,
							$follow_up_1_reached == 'Yes' ? 'Contacted':'Not contacted',
							$follow_up_1_fail_reason,
							$follow_up_1_fail_reason_other,
						]);
					}

					if($follow_up_2 == 'Yes') {

						$follow_up_2_global_record_id = $this->uuid->v4();

						$sql6_etv2 = "INSERT INTO Tracing(RECSTATUS, GlobalRecordId, FirstSaveLogonName, FirstSaveTime, LastSaveLogonName, LastSaveTime, FKEY) VALUES(?,?,?,?,?,?,?)";
						$q6_etv2 = $pdo_etv2->prepare($sql6_etv2);
						$q6_etv2->execute([
							$rec_status,
							$follow_up_2_global_record_id,
							$first_save_logon_name,
							$first_save_time,
							$last_save_logon_name,
							$last_save_time,
							$global_record_id,
						]);

						$sql7_etv2 = "INSERT INTO Tracing3(GlobalRecordId, tracing_date, tracing_method, tracing_outcome, tracing_unsuccessful_reason, tracing_unsuccessful_reason_other) VALUES(?,?,?,?,?,?)";
						$q7_etv2 = $pdo_etv2->prepare($sql7_etv2);
						$q7_etv2->execute([
							$follow_up_2_global_record_id,
							$follow_up_2_date,
							$follow_up_2_method,
							$follow_up_2_reached == 'Yes' ? 'Contacted':'Not contacted',
							$follow_up_2_fail_reason,
							$follow_up_2_fail_reason_other,
						]);
					}

				}
			} else {
				throw new Exception("Error while processing request: Records", 101);
			}

			$this->load->library('zip');
			$this->zip->compression_level = 9;
			$this->zip->read_file(APPPATH.'database/EnrollmentTracker2.prj', 'EnrollmentTracker2/EnrollmentTracker2.prj');
			$this->zip->read_file($etv2, 'EnrollmentTracker2/EnrollmentTracker2.mdb');
			$this->zip->archive($zipv2);
			$this->zip->download('EnrollmentTracker2.zip');
			
    	} catch(Exception $e) {
    		$this->session->set_flashdata('flash_message', $e->getMessage());
    		redirect(base_url('/export'));
    	}
	}
}

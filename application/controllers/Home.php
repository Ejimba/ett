<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$this->load->view('home');
	}

	public function clean()
	{
		$pdo_db = new \PDO("sqlite:".APPPATH.'database/ett.db');
		$sql1_db = "DELETE FROM version_one";
		$q1_db = $pdo_db->prepare($sql1_db);
		$q1_db->execute();
		$this->session->set_flashdata('flash_message', 'Process completed successfully');
		redirect(base_url('/'));
	}
}

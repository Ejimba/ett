<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Records extends CI_Controller {

	public function index()
	{
		$query = "SELECT * FROM version_one";
		$connection = new \PDO("sqlite:".APPPATH.'database/ett.db');
    	$statement = $connection->prepare($query);
    	$statement->execute();
    	$data['records'] = $statement->fetchAll(\PDO::FETCH_OBJ);

        $data['scripts'] = 
        "<script src='js/datatables.min.js'></script>
        <script type='text/javascript'>
        	$(document).ready(function() {
	        	$('.data-table').DataTable();
	        });
	    </script>";
    	$this->load->view('records', $data);
	}
}

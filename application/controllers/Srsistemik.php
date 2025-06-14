<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SRSistemik extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->layout->auth();
	}

	public function index()
	{
		$data['title'] = 'SRSistemik';
		//	$data['subtitle'] = '';
		//	$data['crumb'] = [
		//		'SRSistemik' => '',
		//	];
		//$this->layout->set_privilege(1);
		//	$data['code_js'] = 'srsistemik/codejs';
		$data['page'] = 'srsistemik/Index';
		$this->load->view('template/backend', $data);
	}
}

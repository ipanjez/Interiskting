<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SRJaring extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->layout->auth();
	}

	public function index()
	{
		$data['title'] = 'SRJaring';
		//	$data['subtitle'] = '';
		//	$data['crumb'] = [
		//		'SRJaring' => '',
		//	];
		//$this->layout->set_privilege(1);
		//	$data['code_js'] = 'srjaring/codejs';
		$data['page'] = 'srjaring/Index';
		$this->load->view('template/backend', $data);
	}
}

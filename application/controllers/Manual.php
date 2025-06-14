<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manual extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->layout->auth();
	}

	public function index()
	{
		//	$data['title'] = 'FAQ';
		//	$data['subtitle'] = '';
		//	$data['crumb'] = [
		//		'FAQ' => '',
		//	];
		//$this->layout->set_privilege(1);
		//	$data['code_js'] = 'faq/codejs';
		$data['page'] = 'manual/Index';
		$this->load->view('template/backend', $data);
	}
}

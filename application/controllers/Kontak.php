<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontak extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->layout->auth();
	}

	public function index()
	{
		//	$data['title'] = 'kontak';
		//	$data['subtitle'] = '';
		//	$data['crumb'] = [
		//		'kontak' => '',
		//	];
		//$this->layout->set_privilege(1);
		//	$data['code_js'] = 'kontak/codejs';
		$data['page'] = 'kontak/Index';
		$this->load->view('template/backend', $data);
	}
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grafik extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Grafik_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data['title'] = '';
        $data['subtitle'] = '';
        $data['crumb'] = [
            'Grafik' => '',
        ];
        // $this->load->view('results_view', $data);


        $data['code_js'] = 'grafik/codejs';
        $data['page'] = 'grafik/chart';
        $this->load->view('template/backend', $data);

        // $this->load->view('template/template', $data);
        //  $this->template->load('template', 'charts');
        //  $this->template->load('template/template', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Grafik_model->json();
    }
}

/* End of file Grafik.php */

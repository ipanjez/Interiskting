<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rmi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        //  $this->load->model('Kpi_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data['title'] = '';
        $data['subtitle'] = '';
        $data['crumb'] = [
            'Rmi' => '',
        ];



        // $data['dapel_sai'] = $this->Grafik_model->dapel_sai();
        //$data['daser_sai'] = $this->Grafik_model->daser_sai();



        $data['code_js'] = 'rmi/codejs';
        $data['page'] = 'rmi/rmi_view';
        $this->load->view('template/backend', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Kpi_model->json();
    }
}

/* End of file Grafik.php */

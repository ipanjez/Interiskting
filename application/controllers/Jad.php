<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jad extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Jad_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'jad/codejs';

        $data['page'] = 'jad/Jad_list';
        $this->load->view('template/backend_cal', $data);
    }
    public function index2()
    {

        $data['code_js'] = 'jad/codejs';

        $data['page'] = 'jad/Jad_list_2_kolom';
        $this->load->view('template/backend_cal', $data);
    }
    public function getEvents()
    {
        $result = $this->Jad_model->getEvents();
        echo json_encode($result);
    }
    /*Add new event */
    public function addEvent()
    {
        $result = $this->Jad_model->addEvent();
        echo $result;
    }
    /*Update Event */
    public function updateEvent()
    {
        $result = $this->Jad_model->updateEvent();
        echo $result;
    }
    /*Delete Event*/
    public function deleteEvent()
    {
        $result = $this->Jad_model->deleteEvent();
        echo $result;
    }
    public function dragUpdateEvent()
    {

        $result = $this->Jad_model->dragUpdateEvent();
        echo $result;
    }
}

/* End of file Jad.php */

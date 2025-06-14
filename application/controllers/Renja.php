<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Renja extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Renja_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'renja/codejs';

        $data['page'] = 'renja/Renja_list';
        $this->load->view('template/backend_cal', $data);
    }
    public function index2()
    {

        $data['code_js'] = 'renja/codejs';

        $data['page'] = 'renja/Renja_list_2_kolom';
        $this->load->view('template/backend_cal', $data);
    }
    public function getEvents()
    {
        $result = $this->Renja_model->getEvents();
        echo json_encode($result);
    }
    /*Add new event */
    public function addEvent()
    {
        $result = $this->Renja_model->addEvent();
        echo $result;
    }
    /*Update Event */
    public function updateEvent()
    {
        $result = $this->Renja_model->updateEvent();
        echo $result;
    }
    /*Delete Event*/
    public function deleteEvent()
    {
        $result = $this->Renja_model->deleteEvent();
        echo $result;
    }
    public function dragUpdateEvent()
    {

        $result = $this->Renja_model->dragUpdateEvent();
        echo $result;
    }
}

/* End of file Renja.php */

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Seksi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Seksi_model');
        $this->load->model('Dropdown_model');

        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'seksi/codejs';
        $data['page'] = 'seksi/Seksi_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Seksi_model->json();
    }


    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('seksi/create_action'),
            'id' => set_value('id'),
            'id_bag' => set_value('id_bag'),
            'nama' => set_value('nama')
        );
        $data['dataku'] = $this->Dropdown_model->tampil_data_bagian();


        // var_dump($data);
        // die;

        // print_r($data);
        //exit();

        // cek di sisman_patuh

        $data['page'] = 'seksi/Seksi_form';
        $this->load->view('template/backend', $data);
    }
    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            $data = array(
                'id_bag' => $this->input->post('id_bag', TRUE),
                'nama'     => $this->input->post('nama', TRUE)
            );

            $this->Seksi_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('seksi'));
        }
    }

    public function update($id)
    {
        $row = $this->Seksi_model->get_by_id($id);

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('seksi/update_action'),
                'id' => set_value('id', $row->id),
                'id_bag' => set_value('id_bag', $row->id_bag),
                'nama' => set_value('nama', $row->nama)
            );
            $data['dataku'] = $this->Dropdown_model->tampil_data_bagian();
            $data['page'] = 'seksi/Seksi_form';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('seksi'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {

            $data = array(
                'id_bag' => $this->input->post('id_bag', TRUE),
                'nama' => $this->input->post('nama', TRUE)
            );

            $this->Seksi_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('seksi'));
        }
    }

    public function delete($id)
    {

        $row = $this->Seksi_model->get_by_id($id);


        if ($row) {
            $this->Seksi_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('seksi'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('seksi'));
        }
    }

    public function deletebulk()
    {
        $delete = $this->Seksi_model->deletebulk();
        if ($delete) {
            $this->session->set_flashdata('message', 'Delete Record Success');
        } else {
            $this->session->set_flashdata('message_error', 'Delete Record failed');
        }
        echo $delete;
    }


    public function _rules()
    {

        $this->form_validation->set_rules('id_bag', 'id_bag', 'trim');
        $this->form_validation->set_rules('seksi', 'seksi', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Seksi.php */

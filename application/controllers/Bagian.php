<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bagian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Bagian_model');
        $this->load->model('Dropdown_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'bagian/codejs';
        $data['page'] = 'bagian/Bagian_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Bagian_model->json();
    }


    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('bagian/create_action'),
            'id' => set_value('id'),
            'id_dep' => set_value('id_dep'),
            'nama' => set_value('nama'),
        );

        $data['title'] = 'Bagian';
        $data['subtitle'] = '';
        $data['crumb'] = [
            'Dashboard' => '',
        ];
        $data['dataku'] = $this->Dropdown_model->tampil_data_departemen();
        $data['page'] = 'bagian/Bagian_form';
        $this->load->view('template/backend', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'id_dep' => $this->input->post('id_dep', TRUE),
                'nama' => $this->input->post('nama', TRUE)
            );
            $this->Bagian_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('bagian'));
        }
    }

    public function update($id)
    {
        $row = $this->Bagian_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('bagian/update_action'),
                'id' => set_value('id', $row->id),
                'id_dep' => set_value('id_dep', $row->id_dep),
                'nama' => set_value('nama', $row->nama),
            );
            $data['title'] = 'Bagian';
            $data['subtitle'] = '';
            $data['crumb'] = [
                'Dashboard' => '',
            ];
            $data['dataku'] = $this->Dropdown_model->tampil_data_departemen();
            $data['page'] = 'bagian/Bagian_form';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bagian'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'id_dep' => $this->input->post('id_dep', TRUE),
                'nama' => $this->input->post('nama', TRUE)
            );

            $this->Bagian_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('bagian'));
        }
    }

    public function delete($id)
    {
        $row = $this->Bagian_model->get_by_id($id);

        if ($row) {
            $this->Bagian_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('bagian'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bagian'));
        }
    }

    public function deletebulk()
    {
        $delete = $this->Bagian_model->deletebulk();
        if ($delete) {
            $this->session->set_flashdata('message', 'Delete Record Success');
        } else {
            $this->session->set_flashdata('message_error', 'Delete Record failed');
        }
        echo $delete;
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Bagian.php */

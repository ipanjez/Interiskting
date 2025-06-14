<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Direktorat extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Direktorat_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'direktorat/codejs';
        $data['page'] = 'direktorat/Direktorat_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Direktorat_model->json();
    }


    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('direktorat/create_action'),
            'id' => set_value('id'),
            'nama' => set_value('nama'),
        );
        $data['title'] = 'Direktorat';
        $data['subtitle'] = '';
        $data['crumb'] = [
            'Dashboard' => '',
        ];

        $data['page'] = 'direktorat/Direktorat_form';
        $this->load->view('template/backend', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'nama' => $this->input->post('nama', TRUE),
            );
            $this->Direktorat_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('direktorat'));
        }
    }

    public function update($id)
    {
        $row = $this->Direktorat_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('direktorat/update_action'),
                'id' => set_value('id', $row->id),
                'nama' => set_value('nama', $row->nama),
            );
            $data['title'] = 'Direktorat';
            $data['subtitle'] = '';
            $data['crumb'] = [
                'Dashboard' => '',
            ];

            $data['page'] = 'direktorat/Direktorat_form';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('direktorat'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'nama' => $this->input->post('nama', TRUE),
            );

            $this->Direktorat_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('direktorat'));
        }
    }

    public function delete($id)
    {
        $row = $this->Direktorat_model->get_by_id($id);

        if ($row) {
            $this->Direktorat_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('direktorat'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('direktorat'));
        }
    }

    public function deletebulk()
    {
        $delete = $this->Direktorat_model->deletebulk();
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

/* End of file Direktorat.php */

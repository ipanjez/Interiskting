<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Item extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Item_model');
        $this->load->model('Dropdown_model');
        $this->load->model('Chain_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'item/codejs';
        $data['page'] = 'item/Item_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Item_model->json();
    }


    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('item/create_action'),
            'id' => set_value('id'),
            'id_sek' => set_value('id_sek'),
            'no_item' => set_value('no_item'),
            'nama_item' => set_value('nama_item')
        );

        $data['dir'] = $this->Chain_model->daftar_dir();

        // var_dump($data);
        // die;

        // print_r($data);
        //exit();

        // cek di sisman_patuh

        $data['page'] = 'item/Item_form';
        $this->load->view('template/backend', $data);
    }



    function add_ajax_kom($iddir)
    {
        $query = $this->Chain_model->ajax_kom($iddir);
        $data = "<option value=''>-- Select Kompartemen --</option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->nama . "</option>";
        }
        echo $data;
    }

    function add_ajax_dep($idkom)
    {
        $query = $this->Chain_model->ajax_dep($idkom);
        $data = "<option value=''>-- Pilih Departemen --</option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->nama . "</option>";
        }
        echo $data;
    }

    function add_ajax_bag($iddep)
    {
        $query = $this->Chain_model->ajax_bag($iddep);
        $data = "<option value=''>-- Pilih Bagian --</option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->nama . "</option>";
        }
        echo $data;
    }

    function add_ajax_sek($idbag)
    {
        $query = $this->Chain_model->ajax_sek($idbag);
        $data = "<option value=''>-- Pilih Seksi --</option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->nama_item . "</option>";
        }
        echo $data;
    }
    function add_ajax_ite($idsek)
    {
        //  $query = $this->db->get_where('ite', array('id_sek' => $idsek));
        $query = $this->Chain_model->ajax_ite($idsek);
        $data = "<option value=''>-- Pilih No Item --</option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->no_item . "</option>";
        }
        echo $data;
    }


    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            $data = array(
                'id_sek' => $this->input->post('sek', TRUE),
                'no_item'     => $this->input->post('no_item', TRUE),
                'nama_item'     => $this->input->post('nama_item', TRUE)
            );

            $this->Item_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('item'));
        }
    }

    public function update($id)
    {
        $row = $this->Item_model->get_by_id($id);

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('item/update_action'),
                'id' => set_value('id', $row->id),
                'id_sek' => set_value('id_sek', $row->id_sek),
                'no_item' => set_value('no_item', $row->no_item),
                'nama_item' => set_value('nama_item', $row->nama_item)
            );

            $data['dir'] = $this->Chain_model->daftar_dir();
            $data['page'] = 'item/Item_form';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('item'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {

            $data = array(
                'id_sek' => $this->input->post('sek', TRUE),
                'no_item'     => $this->input->post('no_item', TRUE),
                'nama_item'     => $this->input->post('nama_item', TRUE)
            );

            $this->Item_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('item'));
        }
    }

    public function delete($id)
    {

        $row = $this->Item_model->get_by_id($id);


        if ($row) {
            $this->Item_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('item'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('item'));
        }
    }

    public function deletebulk()
    {
        $delete = $this->Item_model->deletebulk();
        if ($delete) {
            $this->session->set_flashdata('message', 'Delete Record Success');
        } else {
            $this->session->set_flashdata('message_error', 'Delete Record failed');
        }
        echo $delete;
    }


    public function _rules()
    {

        $this->form_validation->set_rules('id_sek', 'id_sek', 'trim');
        $this->form_validation->set_rules('item', 'item', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Item.php */

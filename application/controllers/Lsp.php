<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Lsp extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Lsp_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'lsp/codejs';
        $data['page'] = 'lsp/Lsp_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Lsp_model->json();
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('lsp/create_action'),
            'id' => set_value('id'),
            'jenis' => set_value('jenis'),
            'nama' => set_value('nama')

        );
        $data['title'] = 'Jenis Sertifikasu';
        $data['subtitle'] = '';
        $data['crumb'] = [
            'Dashboard' => '',
        ];

        $data['page'] = 'lsp/Lsp_form';
        $this->load->view('template/backend', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'jenis' => $this->input->post('jenis', TRUE),
                'nama' => $this->input->post('nama', TRUE)
            );
            $this->Lsp_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('lsp'));
        }
    }

    public function update($id)
    {
        $row = $this->Lsp_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('lsp/update_action'),
                'id' => set_value('id', $row->id),
                'jenis' => set_value('jenis', $row->jenis),
                'nama' => set_value('nama',  $row->nama)

            );
            $data['title'] = 'Jenis Sertifikasi';
            $data['subtitle'] = '';
            $data['crumb'] = [
                'Dashboard' => '',
            ];

            $data['page'] = 'lsp/Lsp_form';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('lsp'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'jenis' => $this->input->post('jenis', TRUE),
                'nama' => $this->input->post('nama', TRUE)

            );

            $this->Lsp_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('lsp'));
        }
    }

    public function delete($id)
    {
        $row = $this->Lsp_model->get_by_id($id);

        if ($row) {
            $this->Lsp_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('lsp'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('lsp'));
        }
    }

    public function deletebulk()
    {
        $delete = $this->Lsp_model->deletebulk();
        if ($delete) {
            $this->session->set_flashdata('message', 'Delete Record Success');
        } else {
            $this->session->set_flashdata('message_error', 'Delete Record failed');
        }
        echo $delete;
    }

    public function _rules()
    {
        $this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $semua_pengguna = $this->Lsp_model->getAlllsp()->result();
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)

            ->setCellValue('A1', "No")
            ->setCellValue('B1', "JENIS")
            ->setCellValue('C1', "NAMA");


        $kolom = 2;
        $nomor = 1;
        foreach ($semua_pengguna as $pengguna) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $pengguna->jenis)
                ->setCellValue('C' . $kolom, $pengguna->nama);



            $kolom++;
            $nomor++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="JenisSertifikasi.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}

/* End of file Lsp.php */

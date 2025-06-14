<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class San extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('San_model');
        $this->load->model('Dropdown_model');
        $this->load->model("Commentsan_model");
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data['code_js'] = 'san/codejs';
        $data['page'] = 'san/San_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->San_model->json();
    }

    public function read($id)
    {
        $row = $this->San_model->get_by_id($id);

        if ($row) {
            $data = array(
                'id' => $row->id,
                'email' => $row->email,
                'waktu' => $row->waktu,
                'ph' => $row->ph,
                'bod' => $row->bod,
                'cod' => $row->cod,
                'oil' => $row->oil,
                'tss' => $row->tss,
                'nh3' => $row->nh3,
                'total_coliform' => $row->total_coliform,
                'debit' => $row->debit,
                'npk' => $row->npk,
                'unit_kerja' => $row->unit_kerja,
                'lokasi_sampling' => $row->lokasi_sampling,
                'time_update' => $row->time_update,
                'npk_update' => $row->npk_update,
                'tgl' => $row->tgl,
                'nama_file' => $row->nama_file,
                'cuaca' => $row->cuaca
            );

            $data['commentsan'] = $this->Commentsan_model->get_comment($id);
            $data['page'] = 'san/San_read';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('san'));
        }
    }

    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('san/create_action'),
            'id' => set_value('id'),
            'email' => set_value('email'),
            'ph' => set_value('ph'),
            'bod' => set_value('bod'),
            'cod' => set_value('cod'),
            'oil' => set_value('oil'),
            'tss' => set_value('tss'),
            'nh3' => set_value('nhe'),
            'total_coliform' => set_value('total_coliform'),
            'debit' => set_value('debit'),
            'npk' => set_value('npk'),
            'unit_kerja' => set_value('unit_kerja'),
            'lokasi_sampling' => set_value('lokasi_sampling'),
            'tgl' => set_value('tgl'),
            'npk_update' => set_value('npk'),
            'nama_file' => set_value('nama_file'),
            'cuaca' => set_value('cuaca')
        );
        $data['dataku'] = $this->Dropdown_model->tampil_data_lokasi_sampling();

        $data['page'] = 'san/San_form';
        $this->load->view('template/backend', $data);
    }
    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            $data = array(
                'email'         => $this->session->userdata('email', TRUE),
                'ph'            => $this->input->post('ph', TRUE),
                'bod'           => $this->input->post('bod', TRUE),
                'cod'            => $this->input->post('cod', TRUE),
                'oil'           => $this->input->post('oil', TRUE),
                'tss'            => $this->input->post('tss', TRUE),
                'nh3'           => $this->input->post('nh3', TRUE),
                'total_coliform'         => $this->input->post('total_coliform', TRUE),
                'debit'            => $this->input->post('debit', TRUE),
                'npk'            => $this->session->userdata('npk', TRUE),
                'unit_kerja'     => $this->session->userdata('unit_kerja', TRUE),
                'lokasi_sampling' => $this->input->post('lokasi_sampling', TRUE),
                'npk_update' => $this->session->userdata('npk', TRUE),
                'tgl'            => date('Y-m-d'),
                'bulan'            => date('m'),
                'cuaca'     => $this->input->post('cuaca', TRUE)
            );
            $_FILES['file']['name']     = $_FILES['nama_file']['name'];

            if (!empty($_FILES['nama_file']['name'])) {
                $upload = $this->_do_upload();
                $data['nama_file'] = $upload;
            }
            $this->San_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('san'));
        }
    }
    private function _do_upload()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'jpeg|jpg|png|pdf';
        $config['max_size']             = 11000; //set max size allowed in Kilobyte
        $config['max_width']            = 11000; // set max width image allowed
        $config['max_height']           = 11000; // set max height allowed
        // $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('nama_file')) //upload and validate
        {

            $this->session->set_flashdata('error_msg', $this->upload->display_errors());
            redirect(base_url('san/create')); //show error di form
            exit();
        }
        return $this->upload->data('file_name');
    }
    public function update($id)
    {
        $row = $this->San_model->get_by_id($id);

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('san/update_action'),
                'id' => set_value('id', $row->id),
                'email' => set_value('email', $row->email),
                'ph' => set_value('ph', $row->ph),
                'bod' => set_value('bod', $row->bod),
                'cod' => set_value('cod', $row->cod),
                'oil' => set_value('oil', $row->oil),
                'tss' => set_value('tss', $row->tss),
                'nh3' => set_value('nh3', $row->nh3),
                'total_coliform' => set_value('total_coliform', $row->total_coliform),
                'debit' => set_value('debit', $row->debit),
                'npk' => set_value('npk', $row->npk),
                'unit_kerja' => set_value('unit_kerja', $row->unit_kerja),
                'lokasi_sampling' => set_value('lokasi_sampling', $row->lokasi_sampling),
                'npk_update' => set_value('npk', $row->npk),
                'nama_file' => set_value('nama_file', $row->nama_file),
                'cuaca' => set_value('cuaca', $row->cuaca)
            );
            $data['dataku'] = $this->Dropdown_model->tampil_data_lokasi_sampling();
            $data['page'] = 'san/San_form';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('san'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {

            $data = array(
                'ph'            => $this->input->post('ph', TRUE),
                'bod'           => $this->input->post('bod', TRUE),
                'cod'            => $this->input->post('cod', TRUE),
                'oil'           => $this->input->post('oil', TRUE),
                'tss'            => $this->input->post('tss', TRUE),
                'nh3'           => $this->input->post('nh3', TRUE),
                'total_coliform'         => $this->input->post('total_coliform', TRUE),
                'debit'            => $this->input->post('debit', TRUE),
                'lokasi_sampling' => $this->input->post('lokasi_sampling', TRUE),
                'npk_update' => $this->session->userdata('npk', TRUE),
                'cuaca' => $this->input->post('cuaca', TRUE)

            );
            //file handling
            $_FILES['file']['name']     = $_FILES['nama_file']['name'];

            if ($this->input->post('remove_file')) // if remove file checked
            {
                if (file_exists('uploads/' . $this->input->post('remove_file')) && $this->input->post('remove_file'))
                    unlink('uploads/' . $this->input->post('remove_file'));
                $data['nama_file'] = '';
            }

            if (!empty($_FILES['nama_file']['name'])) {
                $upload = $this->_do_upload();

                //delete file
                //ini fungsi untuk menghapus file
                $san = $this->San_model->get_by_id($this->input->post('id'));
                if (file_exists('uploads/' . $san->nama_file) && $san->nama_file)
                    unlink('uploads/' . $san->nama_file);

                $data['nama_file'] = $upload;
            }
            $this->San_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('san'));
        }
    }

    public function delete($id)
    {
        $san = $this->San_model->get_by_id($id);
        if (file_exists('uploads/' . $san->nama_file) && $san->nama_file)
            unlink('uploads/' . $san->nama_file);
        $row = $this->San_model->get_by_id($id);
        if ($row) {
            $this->San_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('san'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('san'));
        }
    }

    public function deletebulk()
    {
        $delete = $this->San_model->deletebulk();
        if ($delete) {
            $this->session->set_flashdata('message', 'Delete Record Success');
        } else {
            $this->session->set_flashdata('message_error', 'Delete Record failed');
        }
        echo $delete;
    }


    public function _rules()
    {
        $this->form_validation->set_rules('email', 'email', 'trim');
        $this->form_validation->set_rules('ph', 'ph', 'trim');
        $this->form_validation->set_rules('bod', 'bod', 'trim');
        $this->form_validation->set_rules('cod', 'cod', 'trim');
        $this->form_validation->set_rules('oil', 'oil', 'trim');
        $this->form_validation->set_rules('tss', 'tss', 'trim');
        $this->form_validation->set_rules('nh3', 'nh3', 'trim');
        $this->form_validation->set_rules('total_coliform', 'total_coliform', 'trim');
        $this->form_validation->set_rules('debit', 'debit', 'trim');
        $this->form_validation->set_rules('npk', 'npk', 'trim');
        $this->form_validation->set_rules('unit_kerja', 'unit kerja', 'trim');
        $this->form_validation->set_rules('cuaca', 'cuaca', 'trim');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $semua_pengguna = $this->San_model->getAllsan()->result();
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)

            ->setCellValue('A1', "No")
            ->setCellValue('B1', "TGL")
            ->setCellValue('C1', "PH")
            ->setCellValue('D1', "BOD")
            ->setCellValue('E1', "COD")
            ->setCellValue('F1', "OIL")
            ->setCellValue('G1', "TSS")
            ->setCellValue('H1', "NH3")
            ->setCellValue('I1', "TOTAL COLIFORM")
            ->setCellValue('J1', "DEBIT")
            ->setCellValue('K1', "LOKASI SAMPLING")
            ->setCellValue('L1', "NPK")
            ->setCellValue('M1', "CUACA");

        $kolom = 2;
        $nomor = 1;
        foreach ($semua_pengguna as $pengguna) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $pengguna->waktu)
                ->setCellValue('C' . $kolom, $pengguna->ph)
                ->setCellValue('D' . $kolom, $pengguna->bod)
                ->setCellValue('E' . $kolom, $pengguna->cod)
                ->setCellValue('F' . $kolom, $pengguna->oil)
                ->setCellValue('G' . $kolom, $pengguna->tss)
                ->setCellValue('H' . $kolom, $pengguna->nh3)
                ->setCellValue('I' . $kolom, $pengguna->total_coliform)
                ->setCellValue('J' . $kolom, $pengguna->debit)
                ->setCellValue('K' . $kolom, $pengguna->lokasi_sampling)
                ->setCellValue('L' . $kolom, $pengguna->npk)
                ->setCellValue('M' . $kolom, $pengguna->cuaca);
            $kolom++;
            $nomor++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Sanitary.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}

/* End of file San.php */

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Fup2 extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Fup2_model');
        $this->load->model('Departemen_model');
        $this->load->model('Dropdown_model');
        $this->load->model('Chain_model');
        $this->load->model("Commentfup2_model");
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'fup2/codejs';
        $data['page'] = 'fup2/Fup_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Fup2_model->json();
    }

    public function read($id)
    {
        $row = $this->Fup2_model->get_by_id($id);

        // var_dump($row);
        //  die;

        if ($row) {
            $data = array(
                'id' => $row->id,
                'email' => $row->email,
                'waktu' => $row->waktu,
                'judul' => $row->judul,
                'tempat' => $row->tempat,
                'daftar_hadir' => $row->daftar_hadir,
                'resume' => $row->resume,
                'pic_narasumber' => $row->pic_narasumber,
                'tgl_kejadian' => $row->tgl_kejadian,
                'npk' => $row->npk,
                'unit_kerja' => $row->unit_kerja,
                'time_update' => $row->time_update,
                'npk_update' => $row->npk_update,
                'tgl' => $row->tgl,
                'nama_file' => $row->nama_file
            );

            $data['commentfup2'] = $this->Commentfup2_model->get_comment($id);

            $data['page'] = 'fup2/Fup_read';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup2'));
        }
    }

    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('fup2/create_action'),
            'id' => set_value('id'),
            'email' => set_value('email'),
            'waktu' => set_value('waktu'),
            'judul' => set_value('judul'),
            'tempat' => set_value('tempat'),
            'daftar_hadir' => set_value('daftar_hadir'),
            'resume' => set_value('resume'),
            'pic_narasumber' => set_value('pic_narasumber'),
            'tgl_kejadian' => set_value('tgl_kejadian'),
            'npk' => set_value('npk'),
            'unit_kerja' => set_value('unit_kerja'),
            'tgl' => set_value('tgl'),
            'npk_update' => set_value('npk'),
            'nama_file' => set_value('nama_file')
        );


        //  var_dump($data);
        // die;
        $data['page'] = 'fup2/Fup_form';
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
                'waktu' => $this->input->post('waktu', TRUE),
                'judul' => $this->input->post('judul', TRUE),
                'tempat' => $this->input->post('tempat', TRUE),
                'daftar_hadir' => $this->input->post('daftar_hadir', TRUE),
                'resume' => $this->input->post('resume', TRUE),
                'pic_narasumber' => $this->input->post('pic_narasumber', TRUE),
                'tgl_kejadian' => $this->input->post('tgl_kejadian', TRUE),
                'npk'           => $this->session->userdata('npk', TRUE),
                'unit_kerja'    => $this->session->userdata('unit_kerja', TRUE),
                'npk_update' => $this->session->userdata('npk', TRUE),
                'tgl'            => date('Y-m-d'),
                'nama_file'     => $this->input->post('nama_file', TRUE)
            );


            $_FILES['file']['name']     = $_FILES['nama_file']['name'];

            if (!empty($_FILES['nama_file']['name'])) {
                $upload = $this->_do_upload();
                $data['nama_file'] = $upload;
            }
            $this->Fup2_model->insert($data);


            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('fup2'));
        }
    }
    private function _do_upload()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'doc|docx|pdf|jpg|jpeg|png';
        $config['max_size']             = 11000; //set max size allowed in Kilobyte
        $config['max_width']            = 11000; // set max width image allowed
        $config['max_height']           = 11000; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('nama_file')) //upload and validate
        {

            $this->session->set_flashdata('error_msg', $this->upload->display_errors());
            redirect(base_url('fup2/create')); //show error di form
            exit();
        }
        return $this->upload->data('file_name');
    }
    public function update($id)
    {
        $row = $this->Fup2_model->get_by_id($id);
        // var_dump($row);
        // die;
        // print_r($data);
        //exit();

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('fup2/update_action'),
                'id' => set_value('id', $row->id),
                'email' => set_value('email', $row->email),
                'waktu' => set_value('waktu', $row->waktu),
                'judul' => set_value('judul', $row->judul),
                'tempat' => set_value('tempat', $row->tempat),
                'daftar_hadir' => set_value('daftar_hadir', $row->daftar_hadir),
                'resume' => set_value('resume', $row->resume),
                'pic_narasumber' => set_value('pic_narasumber', $row->pic_narasumber),
                'tgl_kejadian' => set_value('tgl_kejadian', $row->tgl_kejadian),
                'npk' => set_value('npk', $row->npk),
                'unit_kerja' => set_value('unit_kerja', $row->unit_kerja),
                'npk_update' => set_value('npk', $row->npk),
                'nama_file' => set_value('nama_file', $row->nama_file)
            );



            $data['page'] = 'fup2/Fup_update';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup2'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {


            $data = array(
                'judul' => $this->input->post('judul', TRUE),
                'tempat' => $this->input->post('tempat', TRUE),
                'daftar_hadir' => $this->input->post('daftar_hadir', TRUE),
                'tgl_kejadian' => $this->input->post('tgl_kejadian', TRUE),
                'resume' => $this->input->post('resume', TRUE),
                'pic_narasumber' => $this->input->post('pic_narasumber', TRUE),
                'tgl_kejadian' => $this->input->post('tgl_kejadian', TRUE),
                'unit_kerja'    => $this->session->userdata('unit_kerja', TRUE),
                'npk_update' => $this->session->userdata('npk', TRUE)

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
                $fup2 = $this->Fup2_model->get_by_id($this->input->post('id'));
                if (file_exists('uploads/' . $fup2->nama_file) && $fup2->nama_file)
                    unlink('uploads/' . $fup2->nama_file);

                $data['nama_file'] = $upload;
            }
            $this->Fup2_model->update($this->input->post('id', TRUE), $data);




            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('fup2'));
        }
    }

    public function delete($id)
    {
        $fup2 = $this->Fup2_model->get_by_id($id);
        if (file_exists('uploads/' . $fup2->nama_file) && $fup2->nama_file)
            unlink('uploads/' . $fup2->nama_file);
        $row = $this->Fup2_model->get_by_id($id);
        if ($row) {
            $this->Fup2_model->delete($id);

            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('fup2'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup2'));
        }
    }

    public function deletebulk()
    {

        $delete = $this->Fup2_model->deletebulk();
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
        $this->form_validation->set_rules('ket', 'ket', 'trim');
        $this->form_validation->set_rules('npk', 'npk', 'trim');
        $this->form_validation->set_rules('unit_kerja', 'unit_kerja', 'trim');
        $this->form_validation->set_rules('lokasi_sampling', 'lokasi_sampling', 'trim');
        $this->form_validation->set_rules('npk_update', 'npk_update', 'trim');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $semua_pengguna = $this->Fup2_model->getAllfup2()->result();
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)

            ->setCellValue('A1', "No")
            ->setCellValue('B1', "TGL")
            ->setCellValue('C1', "KET")
            ->setCellValue('D1', "NAMA FILE");

        $kolom = 2;
        $nomor = 1;
        foreach ($semua_pengguna as $pengguna) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $pengguna->waktu)
                ->setCellValue('C' . $kolom, $pengguna->ket)
                ->setCellValue('D' . $kolom, $pengguna->nama_file);
            $kolom++;
            $nomor++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="file_upload.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}

/* End of file Fup2.php */

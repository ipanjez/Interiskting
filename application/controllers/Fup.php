<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Fup extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Fup_model');
        //  $this->load->model('Departemen_model');
        $this->load->model('Dropdown_model');
        $this->load->model('Chain_model');
        $this->load->model("Commentfup_model");
        $this->load->model("Commentpdu_model");
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'fup/codejs';
        $data['page'] = 'fup/Fup_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Fup_model->json();
    }



    public function read($id)
    {
        $row = $this->Fup_model->get_by_id($id);

        // var_dump($row);
        //die;

        if ($row) {
            $data = array(
                'id' => $row->id,
                'email' => $row->email,
                'waktu' => $row->waktu,
                'nama' => $row->nama,
                'tgl_kejadian' => $row->tgl_kejadian,
                'tempat_kejadian' => $row->tempat_kejadian,
                'jenis_sertifikat' => $row->jenis_sertifikat,
                'no_sertifikat' => $row->no_sertifikat,
                'ket' => $row->ket,
                'npk' => $row->npk,
                'unit_kerja' => $row->unit_kerja,
                'time_update' => $row->time_update,
                'npk_update' => $row->npk_update,
                'tgl' => $row->tgl,
                'nama_file' => $row->nama_file
            );

            $data['commentpdu'] = $this->Commentpdu_model->get_comment($id);
            $data['commentfup'] = $this->Commentfup_model->get_comment($id);

            $data['page'] = 'fup/Fup_read';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup'));
        }
    }

    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('fup/create_action'),
            'id' => set_value('id'),
            'email' => set_value('email'),
            'waktu' => set_value('waktu'),
            'nama' => set_value('nama'),
            'tgl_kejadian' => set_value('tgl_kejadian'),
            'tempat_kejadian' => set_value('tempat_kejadian'),
            'jenis_sertifikat' => set_value('jenis_sertifikat'),
            'no_sertifikat' => set_value('no_sertifikat'),
            'ket' => set_value('ket'),
            'npk' => set_value('npk'),
            'unit_kerja' => set_value('unit_kerja'),
            'tgl' => set_value('tgl'),
            'npk_update' => set_value('npk'),
            'nama_file' => set_value('nama_file')
        );

        $data['dir'] = $this->Chain_model->daftar_dir();
        $data['datajenis'] = $this->Chain_model->tampil_data_jenis_sertifikat();


        $data['page'] = 'fup/Fup_form';
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



    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            $data = array(
                'email'         => $this->session->userdata('email', TRUE),
                'waktu' => $this->input->post('waktu', TRUE),
                'nama' => $this->input->post('nama', TRUE),
                'tgl_kejadian' => $this->input->post('tgl_kejadian', TRUE),
                'ket'            => $this->input->post('ket', TRUE),
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
            ///  var_dump($data);
            //die;
            $this->Fup_model->insert($data);


            //insert tempat kejadian
            $id_fups = $this->db->query("select max(id) as id_fup from fup")->row()->id_fup;
            $data = array(
                'id_tempat_kejadian' => $this->input->post('dep', TRUE),
                'id_fup'       => $id_fups,
                'email'          => $this->session->userdata('email', TRUE)
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
            );

            $this->Fup_model->insert_tempat_kejadian($data);

            //insert jenis sertifikat
            $id_fupjenis = $this->db->query("select max(id) as id_fup from fup")->row()->id_fup;
            $data = array(


                'id_jenis' => $this->input->post('jenis_sertifikat', TRUE),
                'id_fup'       => $id_fupjenis,
                'email'          => $this->session->userdata('email', TRUE),
                'no_sertifikat' => $this->input->post('no_sertifikat', TRUE)
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
            );

            //  var_dump($data);
            //  die;
            // print_r($data);
            //exit();
            $this->Fup_model->insert_sertifikat($data);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('fup'));
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
            redirect(base_url('fup/create')); //show error di form
            exit();
        }
        return $this->upload->data('file_name');
    }
    public function update($id)
    {
        $row = $this->Fup_model->get_by_id($id);

        //  var_dump($row);
        // die;
        // print_r($data);
        //exit();

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('fup/update_action'),
                'id' => set_value('id', $row->id),
                'email' => set_value('email', $row->email),
                'waktu' => set_value('waktu', $row->waktu),
                'nama' => set_value('nama', $row->nama),
                'tgl_kejadian' => set_value('tgl_kejadian', $row->tgl_kejadian),
                'id_tempat_kejadian' => set_value('id_tempat_kejadian',  $row->id_tempat_kejadian),
                'tempat_kejadian' => set_value('tempat_kejadian',  $row->tempat_kejadian),
                'jenis_sertifikat' => set_value('jenis_sertifikat',  $row->jenis_sertifikat),
                'no_sertifikat' => set_value('no_sertifikat',  $row->no_sertifikat),
                'ket' => set_value('ket', $row->ket),
                'npk' => set_value('npk', $row->npk),
                'unit_kerja' => set_value('unit_kerja', $row->unit_kerja),
                'npk_update' => set_value('npk', $row->npk),
                'nama_file' => set_value('nama_file', $row->nama_file)
            );

            //  $data['dataku'] = $this->Dropdown_model->tampil_data_departemen();
            $data['data_sertifikat'] = $this->Dropdown_model->tampil_data_jenis_sertifikat();
            //$data['dir'] = $this->Chain_model->daftar_dir();
            $data['dep'] = $this->Chain_model->daftar_dep();
            //   $data['kom'] = $this->Chain_model->tampil_data_kom();
            //  $data['dep'] = $this->Chain_model->tampil_data_dep();

            $data['page'] = 'fup/Fup_update';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup'));
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
                'tgl_kejadian' => $this->input->post('tgl_kejadian', TRUE),
                'ket' => $this->input->post('ket', TRUE),
                'unit_kerja'    => $this->session->userdata('unit_kerja', TRUE),
                'npk_update' => $this->session->userdata('npk', TRUE)

            );

            // var_dump($data);
            //  die;
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
                $fup = $this->Fup_model->get_by_id($this->input->post('id'));
                if (file_exists('uploads/' . $fup->nama_file) && $fup->nama_file)
                    unlink('uploads/' . $fup->nama_file);

                $data['nama_file'] = $upload;
            }


            $this->Fup_model->update($this->input->post('id', TRUE), $data);
            //u[date tempat kejadian]

            $data = array(
                'email'          => $this->session->userdata('email', TRUE),
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
                'id_tempat_kejadian'      => $this->input->post('id_tempat_kejadian', TRUE),
            );
            $this->Fup_model->update_fup_tempat_kejadian($this->input->post('id', TRUE), $data);

            //update jenis sertifikat

            $data = array(
                'email'          => $this->session->userdata('email', TRUE),
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
                'id_jenis'      => $this->input->post('jenis_sertifikat', TRUE),
                'no_sertifikat'      => $this->input->post('no_sertifikat', TRUE),
            );

            //var_dump($data);
            //die;
            $this->Fup_model->update_fup_sertifikat($this->input->post('id', TRUE), $data);

            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('fup'));
        }
    }

    public function delete($id)
    {
        $fup = $this->Fup_model->get_by_id($id);
        if (file_exists('uploads/' . $fup->nama_file) && $fup->nama_file)
            unlink('uploads/' . $fup->nama_file);
        $row = $this->Fup_model->get_by_id($id);
        if ($row) {
            $this->Fup_model->delete($id);
            $this->Fup_model->delete_fup_tempat_kejadian($id);

            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('fup'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup'));
        }
    }

    public function deletebulk()
    {

        $delete = $this->Fup_model->deletebulk();
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
        $this->form_validation->set_rules('nama', 'nama', 'trim');
        $this->form_validation->set_rules('unit_kerja', 'unit_kerja', 'trim');

        $this->form_validation->set_rules('npk_update', 'npk_update', 'trim');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $semua_pengguna = $this->Fup_model->getAllfup()->result();
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

/* End of file Fup.php */

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Fup3 extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Fup3_model');
        //  $this->load->model('Departemen_model');
        $this->load->model('Dropdown_model');
        $this->load->model('Chain_model');
        $this->load->model("Commentfup3_model");
        $this->load->model("Commentpdu3_model");
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'fup3/codejs';
        $data['page'] = 'fup3/Fup_list';
        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Fup3_model->json();
    }



    public function read($id)
    {
        $row = $this->Fup3_model->get_by_id($id);


        // var_dump($row);
        //  die;

        if ($row) {
            $data = array(
                'id' => $row->id,
                'email' => $row->email,
                'waktu' => $row->waktu,
                'nama' => $row->nama,
                'tahun' => $row->tahun,
                'no_surat_in' => $row->no_surat_in,
                'tgl_in' => $row->tgl_in,
                'no_surat_out' => $row->no_surat_out,
                'tgl_out' => $row->tgl_out,
                'ket' => $row->ket,
                'tempat_kejadian' => $row->tempat_kejadian,
                'id_tempat_kejadian' => $row->id_tempat_kejadian,
                'first_name' => $row->first_name,
                'npk' => $row->npk,
                'unit_kerja' => $row->unit_kerja,
                'time_update' => $row->time_update,
                'npk_update' => $row->npk_update,
                'tgl' => $row->tgl,
                'nama_file' => $row->nama_file
            );

            $data['commentpdu3'] = $this->Commentpdu3_model->get_comment($id);
            $data['commentfup3'] = $this->Commentfup3_model->get_comment($id);

            $data['page'] = 'fup3/Fup_read';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup3'));
        }
    }

    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('fup3/create_action'),
            'id' => set_value('id'),
            'email' => set_value('email'),
            'waktu' => set_value('waktu'),
            'nama' => set_value('nama'),
            'tahun' => set_value('tahun'),
            'no_surat_in' => set_value('no_surat_in'),
            'tgl_in' => set_value('tgl_in'),
            'no_surat_out' => set_value('no_surat_out'),
            'tgl_out' => set_value('tgl_out'),
            'ket' => set_value('ket'),
            'id_users' => set_value('id_users'),
            'npk' => set_value('npk'),
            'unit_kerja' => set_value('unit_kerja'),
            'tgl' => set_value('tgl'),
            'npk_update' => set_value('npk'),
            'nama_file' => set_value('nama_file')
        );

        $data['dir'] = $this->Chain_model->daftar_dir();
        //  $data['datajenis'] = $this->Chain_model->tampil_data_jenis_sertifikat();


        $data['page'] = 'fup3/Fup_form';
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
                'tahun' => $this->input->post('tahun', TRUE),
                'no_surat_in' => $this->input->post('no_surat_in', TRUE),
                'tgl_in' => $this->input->post('tgl_in', TRUE),
                'no_surat_out' => $this->input->post('no_surat_out', TRUE),
                'tgl_out' => $this->input->post('tgl_out', TRUE),
                'ket'            => $this->input->post('ket', TRUE),
                'id_users'           => $this->session->userdata('user_id', TRUE),
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
            $this->Fup3_model->insert($data);

            $id_fups = $this->db->query("select max(id) as id_fup from fup3")->row()->id_fup;
            $data = array(


                'id_tempat_kejadian' => $this->input->post('dep', TRUE),
                'id_fup'       => $id_fups,
                'email'          => $this->session->userdata('email', TRUE)
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
            );

            //  var_dump($data);
            //die;
            // print_r($data);
            //exit();
            $this->Fup3_model->insert_tempat_kejadian($data);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('fup3'));
        }
    }
    private function _do_upload()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'doc|docx|pdf|jpg|jpeg|png';
        $config['max_size']             = 33000; //set max size allowed in Kilobyte
        $config['max_width']            = 11000; // set max width image allowed
        $config['max_height']           = 11000; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('nama_file')) //upload and validate
        {

            $this->session->set_flashdata('error_msg', $this->upload->display_errors());
            redirect(base_url('fup3/create')); //show error di form
            exit();
        }
        return $this->upload->data('file_name');
    }
    public function update($id)
    {
        $row = $this->Fup3_model->get_by_id($id);

        // var_dump($row);
        //die;
        // print_r($data);
        //exit();

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('fup3/update_action'),
                'id' => set_value('id', $row->id),
                'nama' => set_value('nama', $row->nama),
                'tempat_kejadian' => set_value('tempat_kejadian', $row->tempat_kejadian),
                'id_tempat_kejadian' => set_value('tempat_kejadian', $row->id_tempat_kejadian),
                'tahun' => set_value('tahun', $row->tahun),
                'no_surat_in' => set_value('no_surat_in', $row->no_surat_in),
                'tgl_in' => set_value('tgl_in', $row->tgl_in),
                'no_surat_out' => set_value('no_surat_out', $row->no_surat_out),
                'tgl_out' => set_value('tgl_out', $row->tgl_out),
                'ket' => set_value('ket', $row->ket),
                //  'id_users' => set_value('id_users', $row->id_users),
                'npk' => set_value('npk', $row->npk),
                'unit_kerja' => set_value('unit_kerja',  $row->unit_kerja),
                'npk_update' => set_value('npk', $row->npk),
                'nama_file' => set_value('nama_file', $row->nama_file)
            );

            // var_dump($data);
            //die;
            $data['dep'] = $this->Chain_model->daftar_dep();
            $data['page'] = 'fup3/Fup_update';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup3'));
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
                //  'tempat_kejadian' => $this->input->post('tempat_kejadian', TRUE),
                'tahun' => $this->input->post('tahun', TRUE),
                'no_surat_in' => $this->input->post('no_surat_in', TRUE),
                'tgl_in' => $this->input->post('tgl_in', TRUE),
                'no_surat_out' => $this->input->post('no_surat_out', TRUE),
                'tgl_out' => $this->input->post('tgl_out', TRUE),
                'ket'            => $this->input->post('ket', TRUE),
                'id_users'           => $this->session->userdata('user_id', TRUE),
                'unit_kerja'    => $this->session->userdata('unit_kerja', TRUE),
                'npk_update' => $this->session->userdata('npk', TRUE),



            );

            //  var_dump($data);
            // die;
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
                $fup3 = $this->Fup3_model->get_by_id($this->input->post('id'));
                if (file_exists('uploads/' . $fup3->nama_file) && $fup3->nama_file)
                    unlink('uploads/' . $fup3->nama_file);

                $data['nama_file'] = $upload;
            }


            $this->Fup3_model->update($this->input->post('id', TRUE), $data);


            $data = array(
                'email'          => $this->session->userdata('email', TRUE),
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
                'id_tempat_kejadian'      => $this->input->post('id_tempat_kejadian', TRUE),
                //'id_fup'       => $id
            );

            // var_dump($data);
            //die;
            $this->Fup3_model->update_fup3_tempat_kejadian($this->input->post('id', TRUE), $data);


            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('fup3'));
        }
    }

    public function delete($id)
    {
        $fup3 = $this->Fup3_model->get_by_id($id);
        if (file_exists('uploads/' . $fup3->nama_file) && $fup3->nama_file)
            unlink('uploads/' . $fup3->nama_file);
        $row = $this->Fup3_model->get_by_id($id);
        if ($row) {
            $this->Fup3_model->delete($id);
            $this->Fup3_model->delete_fup3_tempat_kejadian($id);

            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('fup3'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup3'));
        }
    }

    public function deletebulk()
    {

        $delete = $this->Fup3_model->deletebulk();
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
        $semua_pengguna = $this->Fup3_model->getAllfup3()->result();
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

/* End of file Fup3.php */

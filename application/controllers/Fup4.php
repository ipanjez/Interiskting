<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Fup4 extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        $this->load->model('Fup4_model');
        //  $this->load->model('Departemen_model');
        $this->load->model('Dropdown_model');
        $this->load->model('Chain_model');
        $this->load->model("Commentfup4_model");
        $this->load->model("Commentpdu4_model");
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        $data['code_js'] = 'fup4/codejs';
        $data['page'] = 'fup4/Fup_list';

        $this->load->view('template/backend', $data);
    }
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Fup4_model->json();
    }
    public function read($id)
    {
        $row = $this->Fup4_model->get_by_id($id);
        //  var_dump($row);
        // die;

        if ($row) {
            $data = array(
                'id' => $row->id,
                'email' => $row->email,
                'waktu' => $row->waktu,
                'risiko' => $row->risiko,
                'judul_kejadian' => $row->judul_kejadian,
                'tgl_kejadian' => $row->tgl_kejadian,
                'tempat_kejadian' => $row->tempat_kejadian,
                'kronologis' => $row->kronologis,
                'penyebab' => $row->penyebab,
                'no_item' => $row->no_item,
                'tindakan' => $row->tindakan,
                'kendala' => $row->kendala,
                'dampak' => $row->dampak,
                'saran' => $row->saran,
                'penanggung_jawab' => $row->penanggung_jawab,
                'ket' => $row->ket,
                'id_tempat_kejadian' => $row->id_tempat_kejadian,
                'first_name' => $row->first_name,
                'npk' => $row->npk,
                'unit_kerja' => $row->unit_kerja,
                'time_update' => $row->time_update,
                'npk_update' => $row->npk_update,
                'tgl' => $row->tgl,
                'nama_file' => $row->nama_file
            );

            $data['commentpdu4'] = $this->Commentpdu4_model->get_comment($id);
            $data['commentfup4'] = $this->Commentfup4_model->get_comment($id);

            $data['page'] = 'fup4/Fup_read';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup4'));
        }
    }

    public function create()
    {

        $data = array(
            'button' => 'Create',
            'action' => site_url('fup4/create_action'),
            'id' => set_value('id'),
            'email' => set_value('email'),
            'waktu' => set_value('waktu'),
            'risiko' => set_value('risiko'),
            'judul_kejadian' => set_value('judul_kejadian'),
            'tgl_kejadian' => set_value('tgl_kejadian'),
            'tempat_kejadian' => set_value('tempat_kejadian'),
            'kronologis' => set_value('kronologis'),
            'penyebab' => set_value('penyebab'),
            'no_item' => set_value('no_item'),
            'tindakan' => set_value('tindakan'),
            'kendala' => set_value('kendala'),
            'dampak' => set_value('dampak'),
            'saran' => set_value('saran'),
            //  'tahun' => set_value('tahun'),
            'penanggung_jawab' => set_value('penanggung_jawab'),
            'first_name' => set_value('first_name'),
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


        $data['page'] = 'fup4/Fup_form';
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
                'risiko' => $this->input->post('risiko', TRUE),
                'judul_kejadian' => $this->input->post('judul_kejadian', TRUE),
                'tgl_kejadian' => $this->input->post('tgl_kejadian', TRUE),
                'tempat_kejadian' => $this->input->post('tempat_kejadian', TRUE),
                'kronologis' => $this->input->post('kronologis', TRUE),
                'penyebab' => $this->input->post('penyebab', TRUE),
                'no_item' => $this->input->post('no_item', TRUE),
                'tindakan' => $this->input->post('tindakan', TRUE),
                'kendala' => $this->input->post('kendala', TRUE),
                'dampak' => $this->input->post('dampak', TRUE),
                'saran' => $this->input->post('saran', TRUE),
                //  'tahun' => $this->input->post('tahun', TRUE),
                'penanggung_jawab' => $this->input->post('penanggung_jawab', TRUE),
                'ket' => $this->input->post('ket', TRUE),
                'id_users'       => $this->session->userdata('user_id', TRUE),
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
            $this->Fup4_model->insert($data);

            $id_fups = $this->db->query("select max(id) as id_fup from fup4")->row()->id_fup;
            $data = array(


                'id_tempat_kejadian' => $this->input->post('dep', TRUE),
                'id_fup'       => $id_fups,
                'email'          => $this->session->userdata('email', TRUE)
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
            );

            // var_dump($data);
            //die;
            // print_r($data);
            //exit();
            $this->Fup4_model->insert_tempat_kejadian($data);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('fup4'));
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
            redirect(base_url('fup4/create')); //show error di form
            exit();
        }
        return $this->upload->data('file_name');
    }
    public function update($id)
    {
        $row = $this->Fup4_model->get_by_id($id);

        // var_dump($row);
        //die;
        // print_r($data);
        //exit();

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('fup4/update_action'),
                'id' => set_value('id', $row->id),
                'judul_kejadian' => set_value('judul_kejadian', $row->judul_kejadian),
                'risiko' => set_value('judul_kejadian', $row->judul_kejadian),
                'tgl_kejadian' => set_value('tgl_kejadian', $row->tgl_kejadian),
                'tempat_kejadian' => set_value('tempat_kejadian', $row->tempat_kejadian),
                'kronologis' => set_value('kronologis', $row->kronologis),
                'penyebab' => set_value('penyebab', $row->penyebab),
                'no_item' => set_value('no_item', $row->no_item),
                'tindakan' => set_value('tindakan', $row->tindakan),
                'kendala' => set_value('kendala', $row->kendala),
                'dampak' => set_value('dampak', $row->dampak),
                'saran' => set_value('saran', $row->saran),
                'penanggung_jawab' => set_value('penanggung_jawab', $row->penanggung_jawab),
                'id_tempat_kejadian' => set_value('tempat_kejadian', $row->id_tempat_kejadian),
                //  'tahun' => set_value('tahun', $row->tahun),
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
            $data['page'] = 'fup4/Fup_update';
            $this->load->view('template/backend', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup4'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {


            $data = array(
                'risiko' => $this->input->post('risiko', TRUE),
                //  'tempat_kejadian' => $this->input->post('tempat_kejadian', TRUE),
                'judul_kejadian' => $this->input->post('judul_kejadian', TRUE),
                'tgl_kejadian' => $this->input->post('tgl_kejadian', TRUE),
                'tempat_kejadian' => $this->input->post('tempat_kejadian', TRUE),
                'kronologis' => $this->input->post('kronologis', TRUE),
                'penyebab' => $this->input->post('penyebab', TRUE),
                'no_item' => $this->input->post('no_item', TRUE),
                'tindakan' => $this->input->post('tindakan', TRUE),
                'kendala' => $this->input->post('kendala', TRUE),
                'dampak' => $this->input->post('dampak', TRUE),
                'saran' => $this->input->post('saran', TRUE),
                //  'tahun' => $this->input->post('tahun', TRUE),
                'ket'            => $this->input->post('ket', TRUE),
                'id_users'           => $this->session->userdata('user_id', TRUE),
                'unit_kerja'    => $this->session->userdata('unit_kerja', TRUE),
                'npk_update' => $this->session->userdata('npk', TRUE)
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
                $fup4 = $this->Fup4_model->get_by_id($this->input->post('id'));
                if (file_exists('uploads/' . $fup4->nama_file) && $fup4->nama_file)
                    unlink('uploads/' . $fup4->nama_file);

                $data['nama_file'] = $upload;
            }


            $this->Fup4_model->update($this->input->post('id', TRUE), $data);


            $data = array(
                'email'          => $this->session->userdata('email', TRUE),
                // 'unit_kerja'     => $this->session->userdata('company', TRUE),
                'id_tempat_kejadian'      => $this->input->post('id_tempat_kejadian', TRUE),
                //'id_fup'       => $id
            );

            // var_dump($data);
            //die;
            $this->Fup4_model->update_fup4_tempat_kejadian($this->input->post('id', TRUE), $data);


            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('fup4'));
        }
    }

    public function delete($id)
    {
        $fup4 = $this->Fup4_model->get_by_id($id);
        if (file_exists('uploads/' . $fup4->nama_file) && $fup4->nama_file)
            unlink('uploads/' . $fup4->nama_file);
        $row = $this->Fup4_model->get_by_id($id);
        if ($row) {
            $this->Fup4_model->delete($id);
            $this->Fup4_model->delete_fup4_tempat_kejadian($id);

            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('fup4'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('fup4'));
        }
    }

    public function deletebulk()
    {

        $delete = $this->Fup4_model->deletebulk();
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
        $semua_pengguna = $this->Fup4_model->getAllfup4()->result();
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)

            ->setCellValue('A1', "No")
            ->setCellValue('B1', "Tgl Kejadian")
            ->setCellValue('C1', "Event")
            ->setCellValue('D1', "Risiko")
            ->setCellValue('E1', "Tempat Kejadian")
            ->setCellValue('F1', "Penyebab")
            ->setCellValue('G1', "No Item")
            ->setCellValue('H1', "Tindakan")
            ->setCellValue('I1', "Kendala")
            ->setCellValue('J1', "Dampak/Akibat")
            ->setCellValue('K1', "Kesimpulan/Saran")
            ->setCellValue('L1', "Ket")
            ->setCellValue('M1', "Penanggung Jawab");

        $kolom = 2;
        $nomor = 1;
        foreach ($semua_pengguna as $pengguna) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $pengguna->tgl_kejadian)
                ->setCellValue('C' . $kolom, $pengguna->judul_kejadian)
                ->setCellValue('D' . $kolom, $pengguna->risiko)
                ->setCellValue('E' . $kolom, $pengguna->tempat_kejadian)
                ->setCellValue('F' . $kolom, $pengguna->penyebab)
                ->setCellValue('G' . $kolom, $pengguna->no_item)
                ->setCellValue('H' . $kolom, $pengguna->tindakan)
                ->setCellValue('I' . $kolom, $pengguna->kendala)
                ->setCellValue('J' . $kolom, $pengguna->dampak)
                ->setCellValue('K' . $kolom, $pengguna->saran)
                ->setCellValue('L' . $kolom, $pengguna->ket)
                ->setCellValue('M' . $kolom, $pengguna->penanggung_jawab);
            $kolom++;
            $nomor++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="file_lem.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}

/* End of file Fup4.php */

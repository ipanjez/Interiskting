<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commentpdu extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Commentpdu_model");
    }
    //Menambahkan komentar

    function index()
    {


        $config['upload_path'] = './uploads';
        $config['allowed_types'] = 'doc|docx|pdf|jpg|jpeg|png';
        $config['max_size'] = 3000;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {

            $fileData = $this->upload->data();

            $upload = [
                'nama_file' => $fileData['file_name'],
                'tipe_file' => $fileData['file_type'],
                'ukuran_file' => $fileData['file_size'],
                'id_users' => $_SESSION['user_id'],
                'id_fup'  => $this->input->post('id_fuppdu')

            ];

            if ($this->Commentpdu_model->add_comment($upload)) {
                $this->session->set_flashdata('success', '<p>File <strong>' . $fileData['file_name'] . '</strong> berhasil diunggah </p>');
                redirect(site_url() . "fup/read/" . $this->input->post('id_fuppdu'));
            } else {
                $this->session->set_flashdata('success', '<p>File <strong>' . $fileData['file_name'] . '</strong> berhasil diunggah </p>');
                // $this->session->set_flashdata('error', '<p>Gagal! File ' . $fileData['file_name'] . ' tidak berhasil tersimpan di database anda</p>');
                redirect(site_url() . "fup/read/" . $this->input->post('id_fuppdu'));
            }


            // redirect(site_url() . "fup/");

        } else {
            $this->session->set_flashdata('error', '<p>Gagal! File  tidak berhasil tersimpan di database anda</p>');
            redirect(site_url() . "fup/read/" . $this->input->post('id_fuppdu'));
        }
    }



    //poistetaan valittu kommentti
    //menghapus komentar yang dipilih
    public function delete($id, $id_fup)
    {
        if (!empty($id)) {

            //  var_dump($id);
            // die;
            $this->db->where('id', $id);
            $this->db->delete('commentpdu');

            //$this->commentpdu->delete_comment($id);

            redirect(site_url() . "fup/read/" . $id_fup);
        }
        echo "Ada yang salah..!!";
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commentfup5 extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Commentfup5_model", "commentfup5");
    }
    //Menambahkan komentar
    public function index()
    {
        //Pengguna telah mengirim komentar
        if ($_POST) {
            $this->commentfup5->add_comment($_POST);
            redirect(site_url() . "fup5/read/" . $this->input->post('id_fup'));
        } else {
            //Tekstin lisäys ei onnistunut ->  Penyisipan teks gagal
            $this->session->set_flashdata('flash_data', 'Error!');
            redirect();
        }
    }



    // menghapus komentar yang dipilih berdasar id
    //setelah dihapus, kembali ke posting berdasarkan id yang diberikan
    public function delete($id, $id_fup)
    {
        if (!empty($id)) {
            $this->commentfup5->delete_comment($id);
            redirect(site_url() . "fup5/read/" . $id_fup);
        }
        echo "Ada yang salah..!!";
    }
}

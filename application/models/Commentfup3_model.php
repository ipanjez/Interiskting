<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commentfup3_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //Lisätään kommentti tietokantaan
    //Tambahkan komentar ke database
    public function add_comment($data)
    {
        $this->db->insert('commentfup3', $data);
    }

    //haetaan kommentit id:n perusteella
    //mengambil komentar dengan id

    public function get_comment($id)
    //public function hae_kommentit($id)
    {
        //as aliasta pitää käyttää jotta kommenteille voidaan antaa oikea id
        // sebagai alias harus digunakan untuk memberi komentar id yang benar

        //poistoa varten
        //untuk dihapus
        $this->db->select('*, commentfup3.id as id');
        $this->db->from('commentfup3');
        $this->db->join('users', 'users.id = commentfup3.id_users');
        $this->db->where('id_fup', $id);
        $this->db->order_by('waktu', 'ASC');
        $kyselyc = $this->db->get();
        return $kyselyc->result();
    }

    //poistetaan valittu kommentti
    //menghapus komentar yang dipilih
    public function delete_comment($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('commentfup3');
    }
}

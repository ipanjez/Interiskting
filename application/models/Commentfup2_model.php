<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commentfup2_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //Lisätään kommentti tietokantaan
    //Tambahkan komentar ke database
    public function add_comment($data)
    {
        $this->db->insert('commentfup2', $data);
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
        $this->db->select('*, commentfup2.id as id');
        $this->db->from('commentfup2');
        $this->db->join('users', 'users.id = commentfup2.id_users');
        $this->db->where('id_fup2', $id);
        $this->db->order_by('waktu', 'ASC');
        $kyselyc = $this->db->get();
        return $kyselyc->result();
    }

    //poistetaan valittu kommentti
    //menghapus komentar yang dipilih
    public function delete_comment($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('commentfup2');
    }

    //poistetaan kommentit, jotka liittyvät annettuun kirjoitukseen
    //menghapus komentar yang terkait dengan pos yang diberikan
    public function poista_kommentit($id)
    {
        $this->db->where('kirjoitus_id', $id);
        $this->db->delete('kommentti');
    }
}

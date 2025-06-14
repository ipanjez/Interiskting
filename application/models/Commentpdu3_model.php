<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commentpdu3_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_comment($data)
    {
        $this->db->insert('commentpdu3', $data);
    }

    public function get_comment($id)
    //public function hae_kommentit($id)
    {
        //as aliasta pitää käyttää jotta kommenteille voidaan antaa oikea id
        // sebagai alias harus digunakan untuk memberi komentar id yang benar

        //poistoa varten
        //untuk dihapus
        $this->db->select('*, commentpdu3.id as id');
        $this->db->from('commentpdu3');
        $this->db->join('users', 'users.id = commentpdu3.id_users');
        $this->db->where('id_fup', $id);
        $this->db->order_by('waktu', 'ASC');
        $kyselyc = $this->db->get();
        return $kyselyc->result();
    }



    public function delete_comment($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('commentpdu3');
    }
}

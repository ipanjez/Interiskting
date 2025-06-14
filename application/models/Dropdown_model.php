<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dropdown_model extends CI_Model
{



    function __construct()
    {
        parent::__construct();
    }

    public function tampil_data_direktorat()
    {
        $query = $this->db->get('dir')->result();
        return $query;
    }

    public function tampil_data_kompartemen()
    {
        $query = $this->db->get('kom')->result();
        return $query;
    }

    public function tampil_data_departemen()
    {
        $query = $this->db->get('dep')->result();
        return $query;
    }

    public function tampil_data_bagian()
    {
        $query = $this->db->get('bag')->result();
        return $query;
    }

    public function tampil_data_seksi()
    {
        $query = $this->db->get('sek')->result();
        return $query;
    }
    public function tampil_data_item()
    {
        $query = $this->db->get('ite')->result();
        return $query;
    }

    /*
    public function tampil_data_lokasi_sampling()
    {
        $query = $this->db->get('lsp')->result();
        return $query;
    }
    */

    public function tampil_data_jenis_sertifikat()
    {
        $query = $this->db->get('lsp')->result();
        return $query;
    }
}

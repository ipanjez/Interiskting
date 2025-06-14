<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Chain_model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }


    public function tampil_data_jenis_sertifikat()
    {
        $query = $this->db->get('lsp')->result();
        return $query;
    }


    function daftar_dir()
    {
        return $this->db->get('dir')->result();
        //  $this->db->select('*')->from('wilayah_provinsi')->get();
        // Tampilkan semua data yang ada di tabel dir
    }
    function daftar_kom()
    {
        return $this->db->get('kom')->result();
        //  $this->db->select('*')->from('wilayah_provinsi')->get();
        // Tampilkan semua data yang ada di tabel kom
    }
    function daftar_dep()
    {
        return $this->db->get('dep')->result();
        //  $this->db->select('*')->from('wilayah_provinsi')->get();
        // Tampilkan semua data yang ada di tabel dep
    }

    function tampil_data_kom($id_dir)
    {
        $this->db->where('id_dir', $id_dir);
        $result = $this->db->get('kom')->result(); // Tampilkan semua data kota berdasarkan id dir

        return $result;
    }

    function ajax_kom($iddir)
    {
        return $this->db->get_where('kom', array('id_dir' => $iddir));
    }

    function ajax_dep($idkom)
    {
        return $this->db->get_where('dep', array('id_kom' => $idkom));
    }

    function ajax_bag($iddep)
    {
        return $this->db->get_where('bag', array('id_dep' => $iddep));
    }

    function ajax_sek($idbag)
    {
        return $this->db->get_where('sek', array('id_bag' => $idbag));
    }

    function ajax_ite($idsek)
    {
        return $this->db->get_where('ite', array('id_sek' => $idsek));
    }
}

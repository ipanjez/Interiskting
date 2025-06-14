<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('ProvinsiModel');
        $this->load->model('KotaModel');
    }

    public function index()
    {

        $get_prov = $this->db->select('*')->from('wilayah_provinsi')->get();

        $data['provinsi'] = $get_prov->result();
        $data['path'] = base_url('assets');

        // $data['provinsi'] = $this->ProvinsiModel->get_provinsi();
        //   $data['provinsi'] = $get_prov->result();
        //var_dump($data);
        //die;
        $this->load->view('form', $data);
    }


    function add_ajax_kab($id_prov)
    {
        $query = $this->db->get_where('wilayah_kabupaten', array('provinsi_id' => $id_prov));
        $data = "<option value=''>- Select Kabupaten -</option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->nama . "</option>";
        }
        echo $data;
    }

    function add_ajax_kec($id_kab)
    {
        $query = $this->db->get_where('wilayah_kecamatan', array('kabupaten_id' => $id_kab));
        $data = "<option value=''> - Pilih Kecamatan - </option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->nama . "</option>";
        }
        echo $data;
    }

    function add_ajax_des($id_kec)
    {
        $query = $this->db->get_where('wilayah_desa', array('kecamatan_id' => $id_kec));
        $data = "<option value=''> - Pilih Desa - </option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->nama . "</option>";
        }
        echo $data;
    }
}

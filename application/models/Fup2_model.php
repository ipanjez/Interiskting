<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fup2_model extends CI_Model
{

    public $table = 'fup2';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json()
    {
        if ($this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
        {
            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('fup2.id, fup2.email, fup2.judul, fup2.tempat,  fup2.daftar_hadir, fup2.resume,fup2.pic_narasumber, fup2.tgl_kejadian, fup2.npk,fup2.unit_kerja,fup2.waktu,fup2.npk_update,fup2.time_update,fup2.nama_file');

            $this->datatables->from('fup2');




            $this->datatables->add_column('action', anchor(site_url('fup2/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Catatan"') . "  " . anchor(site_url('fup2/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup2/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup2/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        } else {

            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('fup2.id, fup2.email, fup2.judul, fup2.tempat,  fup2.daftar_hadir, fup2.resume, fup2.pic_narasumber, fup2.tgl_kejadian, fup2.npk,fup2.unit_kerja,fup2.waktu,fup2.npk_update,fup2.time_update,fup2.nama_file');

            $this->datatables->from('fup2');

            // $this->datatables->join('wajib', 'fup2.id=wajib.fup2_id');
            $this->datatables->where('unit_kerja', $this->session->userdata('unit_kerja'));

            //add this line for join
            //$this->datatables->join('table2', 'fup2.field = table2.field');
            $this->datatables->add_column('action', anchor(site_url('fup2/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Catatan"') . "  " . anchor(site_url('fup2/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup2/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup2/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        }
    }

    //fup2 tempat kejadu





    //fup2 excel
    public function getAllfup2()
    {

        $this->db->select('*');
        $this->db->from('fup2');
        return $this->db->get();
    }

    public function getAll()
    {

        $this->db->select('*');
        $this->db->from('fup2');
        return $this->db->get();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->select('fup2.*');
        $this->db->from('fup2');
        $this->db->where('fup2.id', $id);

        return $this->db->get()->row();
    }



    // get data by email
    function get_by_email($email)
    {
        $this->db->where($this->email,  $this->session->userdata('email'));
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('email', $q);
        $this->db->or_like('judul', $q);
        $this->db->or_like('tempat', $q);
        $this->db->or_like('daftar_hadir', $q);
        $this->db->or_like('resume', $q);
        $this->db->or_like('pic_narasumber', $q);

        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('email', $q);
        $this->db->or_like('judul', $q);
        $this->db->or_like('tempat', $q);
        $this->db->or_like('daftar_hadir', $q);
        $this->db->or_like('resume', $q);
        $this->db->or_like('pic_narasumber', $q);

        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where($this->id, $id);
            $this->db->update($this->table, $data);
        } else {
            $this->db->where($this->id, $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update($this->table, $data);
        }
    }




    // delete data
    function delete($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where($this->id, $id);
            $this->db->delete($this->table);
        } else {
            $this->db->where($this->id, $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->delete($this->table);
        }
    }



    // delete bulkdata
    function deletebulk()
    {
        $data = $this->input->post('msg_', TRUE);
        $arr_id = explode(",", $data);
        $this->db->where_in($this->id, $arr_id);
        return $this->db->delete($this->table);
    }
}

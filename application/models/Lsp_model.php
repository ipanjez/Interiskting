<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lsp_model extends CI_Model
{

    public $table = 'lsp';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json()
    {



        if ($this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
        {
            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('lsp.id,lsp.jenis, lsp.nama');

            $this->datatables->from('lsp');

            $this->datatables->add_column('action', anchor(site_url('lsp/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('lsp/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'lsp/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        } else {

            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('lsp.id,lsp.jenis, lsp.nama');

            $this->datatables->from('lsp');

            // $this->datatables->join('wajib', 'lsp.id=wajib.lsp_id');
            //$this->datatables->join('table2', 'groups.field = table2.field');
            // $this->datatables->where('unit_kerja', $this->session->userdata('unit_kerja'));


            //add this line for join
            //$this->datatables->join('table2', 'patuh.field = table2.field');
            $this->datatables->add_column('action', anchor(site_url('lsp/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('lsp/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'lsp/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        }
    }

    function getGps()
    {
        $query = $this->db->get('lsp');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function getAlllsp()
    {

        $this->db->select('*');
        $this->db->from('lsp');
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
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
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
        $this->db->or_like('jenis', $q);
        $this->db->or_like('nama', $q);

        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('jenis', $q);
        $this->db->or_like('bujur', $q);

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
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
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

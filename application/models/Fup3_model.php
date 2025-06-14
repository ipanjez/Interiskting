<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fup3_model extends CI_Model
{

    public $table = 'fup3';
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
            $this->datatables->select('fup3.id, fup3.email, fup3.nama, fup3.tahun, fup3.no_surat_in,fup3.tgl_in,  no_surat_out,fup3.tgl_out, fup3.ket,fup3.id_users,fup3.npk,fup3.unit_kerja,fup3.waktu,fup3.npk_update,fup3.time_update,fup3.nama_file, dep.nama as tempat_kejadian, (SELECT commentfup3.keterangan FROM commentfup3 WHERE fup3.id = commentfup3.id_fup  ORDER BY commentfup3.waktu DESC LIMIT 1)  ket_mon');

            $this->datatables->from('fup3');

            $this->datatables->join('fup3_tempat_kejadian', 'fup3_tempat_kejadian.id_fup = fup3.id');
            $this->datatables->join('dep', 'dep.id = fup3_tempat_kejadian.id_tempat_kejadian');


            // $this->datatables->join('fup3_sebab_kejadian', 'fup3_sebab_kejadian.id_fup = fup3.id');
            //$this->datatables->join('ite', 'ite.id = fup3_sebab_kejadian.id_sebab_kejadian');


            $this->datatables->add_column('action', anchor(site_url('fup3/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup3/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup3/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup3/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        } else {

            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('fup3.id, fup3.email, fup3.nama, fup3.tahun, fup3.no_surat_in,fup3.tgl_in,  no_surat_out,fup3.tgl_out, fup3.ket,fup3.id_users,fup3.npk,fup3.unit_kerja,fup3.waktu,fup3.npk_update,fup3.time_update,fup3.nama_file, dep.nama as tempat_kejadian, (SELECT commentfup3.keterangan FROM commentfup3 WHERE fup3.id = commentfup3.id_fup  ORDER BY commentfup3.waktu DESC LIMIT 1)  ket_mon');

            $this->datatables->from('fup3');

            // $this->datatables->join('wajib', 'fup3.id=wajib.fup3_id');
            $this->datatables->where('unit_kerja', $this->session->userdata('unit_kerja'));

            //add this line for join
            //$this->datatables->join('table2', 'fup3.field = table2.field');
            $this->datatables->add_column('action', anchor(site_url('fup3/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup3/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup3/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup3/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        }
    }

    //fup3 tempat kejadu

    function insert_tempat_kejadian($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup3_tempat_kejadian', $data);
        } else {
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup3_tempat_kejadian', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }

    function insert_sebab_kejadian($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup3_sebab_kejadian', $data);
        } else {

            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup3_sebab_kejadian', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }



    //fup3 excel
    public function getAllfup3()
    {

        $this->db->select('*');
        $this->db->from('fup3');
        return $this->db->get();
    }

    public function getAll()
    {

        $this->db->select('*');
        $this->db->from('fup3');
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
        $this->db->select('fup3.id, fup3.nama,fup3.email,fup3.tahun,fup3.no_surat_in, fup3.tgl_in, fup3.no_surat_out, fup3.tgl_out, fup3.ket, fup3.waktu, fup3.time_update,fup3.npk_update,fup3.tgl, fup3.nama_file, dep.nama as tempat_kejadian, fup3_tempat_kejadian.id_tempat_kejadian, users.first_name, users.last_login, users.npk, users.unit_kerja');
        $this->db->from('fup3');

        $this->db->join('fup3_tempat_kejadian', 'fup3_tempat_kejadian.id_fup = fup3.id');
        $this->db->join('dep', 'dep.id = fup3_tempat_kejadian.id_tempat_kejadian');
        $this->db->join('users', 'users.id = fup3.id_users');

        //  $this->db->join('fup3_sebab_kejadian', 'fup3_sebab_kejadian.id_fup = fup3.id');
        //$this->db->join('ite', 'ite.id = fup3_sebab_kejadian.id_sebab_kejadian');
        //$this->db->join('sek', 'sek.id = ite.id_sek');
        //$this->db->join('bag', 'bag.id = sek.id_bag');


        $this->db->where('fup3.id', $id);

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
        $this->db->like('id', $q);
        $this->db->or_like('email', $q);
        $this->db->or_like('nama', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('no_surat_in', $q);
        $this->db->or_like('ket', $q);
        $this->db->or_like('username', $q);
        $this->db->or_like('npk', $q);
        $this->db->or_like('unit_kerja', $q);
        $this->db->or_like('waktu', $q);
        $this->db->or_like('nama_file', $q);
        $this->db->or_like('tempat_kejadian', $q);
        $this->db->or_like('ket_mon', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('email', $q);
        $this->db->or_like('nama', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('no_surat_in', $q);
        $this->db->or_like('ket', $q);
        $this->db->or_like('username', $q);
        $this->db->or_like('npk', $q);
        $this->db->or_like('unit_kerja', $q);
        $this->db->or_like('waktu', $q);
        $this->db->or_like('nama_file', $q);
        $this->db->or_like('tempat_kejadian', $q);
        $this->db->or_like('ket_mon', $q);

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


    function update_fup3_tempat_kejadian($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup3_tempat_kejadian', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup3_tempat_kejadian', $data);
        }
    }

    function update_fup3_sebab_kejadian($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup3_sebab_kejadian', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup3_sebab_kejadian', $data);
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

    function delete_fup3_tempat_kejadian($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup3_tempat_kejadian');
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->delete($this->table);
        }
    }

    function delete_fup3_sebab_kejadian($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup3_sebab_kejadian');
        } else {
            $this->db->where('id_fup', $id);
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

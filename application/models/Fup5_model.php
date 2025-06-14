<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fup5_model extends CI_Model
{

    public $table = 'fup5';
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
            $this->datatables->select('fup5.id, fup5.email, fup5.tugas, fup5.pic, fup5.resume, fup5.tgl_deadline, fup5.id_users, fup5.npk, fup5.unit_kerja, fup5.waktu,fup5.npk_update, fup5.time_update, fup5.nama_file, dep.nama as tempat_kejadian, (SELECT commentfup5.keterangan FROM commentfup5 WHERE fup5.id = commentfup5.id_fup  ORDER BY commentfup5.waktu DESC LIMIT 1) ket_mon');

            $this->datatables->from('fup5');

            $this->datatables->join('fup5_tempat_kejadian', 'fup5_tempat_kejadian.id_fup = fup5.id');
            $this->datatables->join('dep', 'dep.id = fup5_tempat_kejadian.id_tempat_kejadian');





            $this->datatables->add_column('action', anchor(site_url('fup5/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup5/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup5/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup5/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        } else {

            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('fup5.id, fup5.email, fup5.tugas, fup5.pic, fup5.resume, fup5.tgl_deadline, fup5.id_users,fup5.npk,fup5.unit_kerja,fup5.waktu,fup5.npk_update,fup5.time_update,fup5.nama_file, dep.nama as tempat_kejadian, (SELECT commentfup5.keterangan FROM commentfup5 WHERE fup5.id = commentfup5.id_fup  ORDER BY commentfup5.waktu DESC LIMIT 1)  ket_mon');

            $this->datatables->from('fup5');

            // $this->datatables->join('wajib', 'fup5.id=wajib.fup5_id');
            $this->datatables->where('unit_kerja', $this->session->userdata('unit_kerja'));

            //add this line for join
            //$this->datatables->join('table2', 'fup5.field = table2.field');
            $this->datatables->add_column('action', anchor(site_url('fup5/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup5/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup5/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup5/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        }
    }

    //fup5 tempat kejadu

    function insert_tempat_kejadian($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup5_tempat_kejadian', $data);
        } else {
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup5_tempat_kejadian', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }

    function insert_sebab_kejadian($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup5_sebab_kejadian', $data);
        } else {

            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup5_sebab_kejadian', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }



    //fup5 excel
    public function getAllfup5()
    {

        $this->db->select('*');
        $this->db->from('fup5');
        return $this->db->get();
    }

    public function getAll()
    {

        $this->db->select('*');
        $this->db->from('fup5');
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
        $this->db->select('fup5.id,  fup5.email, fup5.tugas, fup5.pic, fup5.resume, fup5.tgl_deadline, fup5.waktu, fup5.time_update,fup5.npk_update,fup5.tgl, fup5.nama_file, dep.nama as tempat_kejadian, fup5_tempat_kejadian.id_tempat_kejadian, users.first_name, users.last_login, users.npk, users.unit_kerja');
        $this->db->from('fup5');

        $this->db->join('fup5_tempat_kejadian', 'fup5_tempat_kejadian.id_fup = fup5.id');
        $this->db->join('dep', 'dep.id = fup5_tempat_kejadian.id_tempat_kejadian');
        $this->db->join('users', 'users.id = fup5.id_users');

        $this->db->where('fup5.id', $id);

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
        $this->db->or_like('tugas', $q);
        $this->db->or_like('pic', $q);
        $this->db->or_like('resume', $q);
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
        $this->db->or_like('tugas', $q);
        $this->db->or_like('pic', $q);
        $this->db->or_like('resume', $q);
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


    function update_fup5_tempat_kejadian($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup5_tempat_kejadian', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup5_tempat_kejadian', $data);
        }
    }

    function update_fup5_sebab_kejadian($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup5_sebab_kejadian', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup5_sebab_kejadian', $data);
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

    function delete_fup5_tempat_kejadian($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup5_tempat_kejadian');
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->delete($this->table);
        }
    }

    function delete_fup5_sebab_kejadian($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup5_sebab_kejadian');
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

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fup_model extends CI_Model
{

    public $table = 'fup';
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
            $this->datatables->select('fup.id, fup.email, fup.nama,fup.tgl_kejadian, fup.ket,fup.npk,fup.unit_kerja,fup.waktu,fup.npk_update,fup.time_update,fup.nama_file, dep.nama as tempat_kejadian, lsp.jenis as jenis_sertifikat, fup_sertifikat.no_sertifikat as no_sertifikat,(SELECT commentfup.keterangan FROM commentfup WHERE fup.id = commentfup.id_fup  ORDER BY commentfup.waktu DESC LIMIT 1)  ket_mon');

            $this->datatables->from('fup');

            $this->datatables->join('fup_tempat_kejadian', 'fup_tempat_kejadian.id_fup = fup.id');
            $this->datatables->join('fup_sertifikat', 'fup_sertifikat.id_fup = fup.id');
            $this->datatables->join('lsp', 'lsp.id = fup_sertifikat.id_jenis');
            $this->datatables->join('dep', 'dep.id = fup_tempat_kejadian.id_tempat_kejadian');



            // $this->datatables->join('fup_sebab_kejadian', 'fup_sebab_kejadian.id_fup = fup.id');
            //$this->datatables->join('ite', 'ite.id = fup_sebab_kejadian.id_sebab_kejadian');


            $this->datatables->add_column('action', anchor(site_url('fup/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        } else {

            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('fup.id, fup.email, fup.nama, fup.jenis_sertifikat, fup.no_sertifikat,fup.tgl_kejadian, fup.ket,fup.npk,fup.unit_kerja,fup.waktu,fup.npk_update,fup.time_update,fup.nama_file, dep.nama as tempat_kejadian, (SELECT commentfup.keterangan FROM commentfup WHERE fup.id = commentfup.id_fup  ORDER BY commentfup.waktu DESC LIMIT 1)  ket_mon');

            $this->datatables->from('fup');

            // $this->datatables->join('wajib', 'fup.id=wajib.fup_id');
            $this->datatables->where('unit_kerja', $this->session->userdata('unit_kerja'));

            //add this line for join
            //$this->datatables->join('table2', 'fup.field = table2.field');
            $this->datatables->add_column('action', anchor(site_url('fup/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        }
    }

    //fup tempat kejadu

    function insert_tempat_kejadian($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup_tempat_kejadian', $data);
        } else {
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup_tempat_kejadian', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }

    function insert_sertifikat($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup_sertifikat', $data);
        } else {

            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup_sertifikat', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }



    //fup excel
    public function getAllfup()
    {

        $this->db->select('*');
        $this->db->from('fup');
        return $this->db->get();
    }

    public function getAll()
    {

        $this->db->select('*');
        $this->db->from('fup');
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
        $this->db->select('fup.*, dep.nama as tempat_kejadian, fup_tempat_kejadian.id_tempat_kejadian, lsp.jenis as jenis_sertifikat, fup_sertifikat.no_sertifikat as no_sertifikat');
        $this->db->from('fup');

        $this->db->join('fup_tempat_kejadian', 'fup_tempat_kejadian.id_fup = fup.id');
        $this->db->join('dep', 'dep.id = fup_tempat_kejadian.id_tempat_kejadian');

        $this->db->join('fup_sertifikat', 'fup_sertifikat.id_fup = fup.id');
        $this->db->join('lsp', 'lsp.id = fup_sertifikat.id_jenis');


        //  $this->db->join('fup_sebab_kejadian', 'fup_sebab_kejadian.id_fup = fup.id');
        //$this->db->join('ite', 'ite.id = fup_sebab_kejadian.id_sebab_kejadian');
        //$this->db->join('sek', 'sek.id = ite.id_sek');
        //$this->db->join('bag', 'bag.id = sek.id_bag');


        $this->db->where('fup.id', $id);

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
        $this->db->or_like('tgl_kejadian', $q);
        $this->db->or_like('jenis_sertifikat', $q);
        $this->db->or_like('no_sertifikat', $q);
        $this->db->or_like('ket', $q);
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
        $this->db->or_like('tgl_kejadian', $q);
        $this->db->or_like('jenis_sertifikat', $q);
        $this->db->or_like('no_sertifikat', $q);
        $this->db->or_like('ket', $q);
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


    function update_fup_tempat_kejadian($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup_tempat_kejadian', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup_tempat_kejadian', $data);
        }
    }

    function update_fup_sertifikat($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup_sertifikat', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup_sertifikat', $data);
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

    function delete_fup_tempat_kejadian($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup_tempat_kejadian');
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->delete($this->table);
        }
    }

    function delete_fup_sertifikat($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup_sertifikat');
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

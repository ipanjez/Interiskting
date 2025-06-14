<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class fup4_model extends CI_Model
{

    public $table = 'fup4';
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
            $this->datatables->select('fup4.id, fup4.email, fup4.risiko, fup4.judul_kejadian, fup4.tgl_kejadian, fup4.tempat_kejadian, fup4.kronologis, fup4.penyebab, fup4.no_item, fup4.tindakan, fup4.kendala, fup4.dampak, fup4.saran, fup4.penanggung_jawab, fup4.ket, fup4.id_users,fup4.npk, fup4.unit_kerja, fup4.waktu,fup4.npk_update,fup4.time_update, fup4.tgl, fup4.nama_file, dep.nama as tempat_kejadian, (SELECT commentfup4.keterangan FROM commentfup4 WHERE fup4.id = commentfup4.id_fup  ORDER BY commentfup4.waktu DESC LIMIT 1)  ket_mon');
            $this->datatables->from('fup4');
            $this->datatables->join('fup4_tempat_kejadian', 'fup4_tempat_kejadian.id_fup = fup4.id');
            $this->datatables->join('dep', 'dep.id = fup4_tempat_kejadian.id_tempat_kejadian');


            // $this->datatables->join('fup4_sebab_kejadian', 'fup4_sebab_kejadian.id_fup = fup4.id');
            //$this->datatables->join('ite', 'ite.id = fup4_sebab_kejadian.id_sebab_kejadian');


            $this->datatables->add_column('action', anchor(site_url('fup4/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup4/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup4/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup4/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        } else {

            // redirect them to the home page because they must be an administrator to view this
            $this->datatables->select('fup4.id, fup4.email, fup4.risiko, fup4.judul_kejadian, fup4.tgl_kejadian, fup4.tempat_kejadian, fup4.kronologis, fup4.penyebab, fup4.no_item, fup4.tindakan, fup4.kendala, fup4.dampak, fup4.saran, fup4.penanggung_jawab, fup4.ket, fup4.id_users,fup4.npk, fup4.unit_kerja, fup4.waktu,fup4.npk_update,fup4.time_update, fup4.tgl, fup4.nama_file, dep.nama as tempat_kejadian, (SELECT commentfup4.keterangan FROM commentfup4 WHERE fup4.id = commentfup4.id_fup  ORDER BY commentfup4.waktu DESC LIMIT 1)  ket_mon');

            $this->datatables->from('fup4');

            // $this->datatables->join('wajib', 'fup4.id=wajib.fup4_id');
            $this->datatables->where('unit_kerja', $this->session->userdata('unit_kerja'));

            //add this line for join
            //$this->datatables->join('table2', 'fup4.field = table2.field');
            $this->datatables->add_column('action', anchor(site_url('fup4/read/$1'), '<i class="fa fa-search"></i>', 'class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Lihat"') . "  " . anchor(site_url('fup4/update/$1'), '<i class="fa fa-edit"></i>', 'class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit"') . "  " . anchor(site_url('fup4/delete/$1'), '<i class="fa fa-trash"></i>', 'class="btn btn-danger btn-sm" onclick="return confirmdelete(\'fup4/delete/$1\')" data-toggle="tooltip" title="Delete"'), 'id');
            return $this->datatables->generate();
        }
    }

    //fup4 tempat kejadu

    function insert_tempat_kejadian($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup4_tempat_kejadian', $data);
        } else {
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup4_tempat_kejadian', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }

    function insert_sebab_kejadian($data)
    {

        if ($this->ion_auth->is_admin()) {
            $this->db->insert('fup4_sebab_kejadian', $data);
        } else {

            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->where('unit_kerja =', $this->session->userdata('company'));
            $this->db->insert('fup4_sebab_kejadian', $data);
        }

        //  $this->db->insert('sisman_patuh', $data);
    }



    //fup4 excel
    public function getAllfup4()
    {

        $this->db->select('*');
        $this->db->from('fup4');
        return $this->db->get();
    }

    public function getAll()
    {

        $this->db->select('*');
        $this->db->from('fup4');
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
        $this->db->select('fup4.id, fup4.email, fup4.risiko, fup4.judul_kejadian, fup4.tgl_kejadian, fup4.tempat_kejadian, fup4.kronologis, fup4.penyebab, fup4.no_item, fup4.tindakan, fup4.kendala, fup4.dampak, fup4.saran,  fup4.penanggung_jawab, fup4.ket, fup4.id_users,fup4.npk, fup4.unit_kerja, fup4.waktu,fup4.npk_update,fup4.time_update, fup4.tgl, fup4.nama_file, dep.nama as tempat_kejadian, fup4_tempat_kejadian.id_tempat_kejadian, users.first_name, users.last_login, users.npk, users.unit_kerja');
        $this->db->from('fup4');

        $this->db->join('fup4_tempat_kejadian', 'fup4_tempat_kejadian.id_fup = fup4.id');
        $this->db->join('dep', 'dep.id = fup4_tempat_kejadian.id_tempat_kejadian');
        $this->db->join('users', 'users.id = fup4.id_users');

        //  $this->db->join('fup4_sebab_kejadian', 'fup4_sebab_kejadian.id_fup = fup4.id');
        //$this->db->join('ite', 'ite.id = fup4_sebab_kejadian.id_sebab_kejadian');
        //$this->db->join('sek', 'sek.id = ite.id_sek');
        //$this->db->join('bag', 'bag.id = sek.id_bag');


        $this->db->where('fup4.id', $id);

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
        $this->db->or_like('risiko', $q);
        $this->db->or_like('judul_kejadian', $q);
        $this->db->or_like('tgl_kejadian', $q);
        $this->db->or_like('tempat_kejadian', $q);
        $this->db->or_like('kronologis', $q);
        $this->db->or_like('penyebab', $q);
        $this->db->or_like('no_item', $q);
        $this->db->or_like('tindakan', $q);
        $this->db->or_like('kendala', $q);
        $this->db->or_like('dampak', $q);
        $this->db->or_like('saran', $q);

        $this->db->or_like('penanggung_jawab', $q);
        $this->db->or_like('ket', $q);
        $this->db->or_like('first_name', $q);
        $this->db->or_like('npk', $q);
        $this->db->or_like('unit_kerja', $q);
        $this->db->or_like('waktu', $q);
        $this->db->or_like('tgl', $q);
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
        $this->db->or_like('risiko', $q);
        $this->db->or_like('judul_kejadian', $q);
        $this->db->or_like('tgl_kejadian', $q);
        $this->db->or_like('tempat_kejadian', $q);
        $this->db->or_like('kronologis', $q);
        $this->db->or_like('penyebab', $q);
        $this->db->or_like('no_item', $q);
        $this->db->or_like('tindakan', $q);
        $this->db->or_like('kendala', $q);
        $this->db->or_like('dampak', $q);
        $this->db->or_like('saran', $q);
        $this->db->or_like('penanggung_jawab', $q);
        $this->db->or_like('ket', $q);
        $this->db->or_like('first_name', $q);
        $this->db->or_like('npk', $q);
        $this->db->or_like('unit_kerja', $q);
        $this->db->or_like('waktu', $q);
        $this->db->or_like('tgl', $q);
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


    function update_fup4_tempat_kejadian($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup4_tempat_kejadian', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup4_tempat_kejadian', $data);
        }
    }

    function update_fup4_sebab_kejadian($id, $data)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->update('fup4_sebab_kejadian', $data);
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->update('fup4_sebab_kejadian', $data);
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

    function delete_fup4_tempat_kejadian($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup4_tempat_kejadian');
        } else {
            $this->db->where('id_fup', $id);
            $this->db->where('email =', $this->session->userdata('email'));
            $this->db->or_where('unit_kerja =', $this->session->userdata('unit_kerja'));
            $this->db->delete($this->table);
        }
    }

    function delete_fup4_sebab_kejadian($id)
    {
        if ($this->ion_auth->is_admin()) {
            $this->db->where('id_fup', $id);
            $this->db->delete('fup4_sebab_kejadian');
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

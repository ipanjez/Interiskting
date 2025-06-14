<?php
// Grafik.php
class Grafik_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->database();
    }

    function json()
    {
        //  $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y')); //waktu tHUN KEMARIN karena tahun ini o
        // $this->db->group_by('unit_kerja');
        //$this->db->select('unit_kerja');
        //$this->db->select("count(*) as total");
        //return $this->db->from('patuh')
        //  ->get()
        // ->result();

        $this->datatables->select('patuh.id,patuh.unit_kerja');
        $this->datatables->group_by('unit_kerja');
        $this->datatables->select("count(*) as total");
        $this->datatables->from('patuh');
        //$this->datatables->limit(4);
        $this->datatables->where("DATE_FORMAT(waktu,'%Y')", date('Y')); //waktu tHUN KEMARIN karena tahun ini 0
        return $this->datatables->generate();
    }

    function jum_patuh_peruk_thn_ini()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y')); //waktu tHUN KEMARIN karena tahun ini o
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('patuh')
            ->get()
            ->result();
    }

    function jum_patuh_peruk_thn_lalu()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years'))); //waktu 2 tHUN lalu
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('patuh')
            ->get()
            ->result();
    }

    function jum_bulanan_peruk_thn_ini()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_bulanan')
            ->get()
            ->result();
    }

    function jum_bulanan_peruk_thn_lalu()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_bulanan')
            ->get()
            ->result();
    }

    function jum_triwulan_peruk_thn_ini()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_triwulan')
            ->get()
            ->result();
    }

    function jum_triwulan_peruk_thn_lalu()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_triwulan')
            ->get()
            ->result();
    }

    function jum_semester_peruk_thn_ini()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_semester')
            ->get()
            ->result();
    }

    function jum_semester_peruk_thn_lalu()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_semester')
            ->get()
            ->result();
    }

    function jum_tahun_peruk_thn_ini()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_tahun')
            ->get()
            ->result();
    }

    function jum_tahun_peruk_thn_lalu()
    {
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $this->db->group_by('unit_kerja');
        $this->db->select('unit_kerja');
        $this->db->select("count(*) as total");
        return $this->db->from('wajib_tahun')
            ->get()
            ->result();
    }

    function total_uk_thn_ini()
    {
        //  $this->db->count_all_results();  //hitung jumlah unit kerja
        $this->db->select('unit_kerja');
        $this->db->distinct();

        // $this->db->from('patuh');
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $query = $this->db->get('patuh');
        // return count($query->result());
        return $query->num_rows();
    }
    function total_uk_thn_lalu()
    {
        //  $this->db->count_all_results();
        $this->db->select('unit_kerja');
        $this->db->distinct();

        // $this->db->from('patuh');
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $query = $this->db->get('patuh');
        // return count($query->result());
        return $query->num_rows();
    }


    function total_patuh_thn_ini()
    {

        $this->db->select('nama_peraturan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $query = $this->db->get('patuh');
        return $query->num_rows();
    }

    function total_patuh_thn_lalu()
    {

        $this->db->select('nama_peraturan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $query = $this->db->get('patuh');
        return $query->num_rows();
    }

    function total_wajib_bulanan_thn_ini()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $query = $this->db->get('wajib_bulanan');
        return $query->num_rows();
    }

    function total_wajib_bulanan_thn_lalu()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $query = $this->db->get('wajib_bulanan');
        return $query->num_rows();
    }

    function total_wajib_triwulan_thn_ini()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $query = $this->db->get('wajib_triwulan');
        return $query->num_rows();
    }

    function total_wajib_triwulan_thn_lalu()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $query = $this->db->get('wajib_triwulan');
        return $query->num_rows();
    }

    function total_wajib_semester_thn_ini()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $query = $this->db->get('wajib_semester');
        return $query->num_rows();
    }

    function total_wajib_semester_thn_lalu()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $query = $this->db->get('wajib_semester');
        return $query->num_rows();
    }




    function total_wajib_tahun_thn_ini()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y'));
        $query = $this->db->get('wajib_tahun');
        return $query->num_rows();
    }

    function total_wajib_tahun_thn_lalu()
    {

        $this->db->select('nama_laporan');
        $this->db->distinct();
        $this->db->where("DATE_FORMAT(waktu,'%Y')", date('Y', strtotime('-1 years')));
        $query = $this->db->get('wajib_tahun');
        return $query->num_rows();
    }
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Renja_model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    public function getEvents()
    {

        $sql = "SELECT * FROM rencana WHERE rencana.start BETWEEN ? AND ? ORDER BY rencana.start ASC";
        return $this->db->query($sql, array($_GET['start'], $_GET['end']))->result();
    }

    /*Create new rencana */

    public function addEvent()
    {

        $sql = "INSERT INTO rencana (title,rencana.start,rencana.end,description,pic,link,waktu,target,tls,color) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $this->db->query($sql, array($_POST['title'], $_POST['start'], $_POST['end'], $_POST['description'], $_POST['pic'], $_POST['link'], $_POST['waktu'], $_POST['target'], $_POST['tls'], $_POST['color']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /*Update  event */

    public function updateEvent()
    {

        $sql = "UPDATE rencana SET title = ?, description = ?,  pic = ?, link = ?, waktu = ?, target = ?, tls = ?, color = ? WHERE id = ?";
        $this->db->query($sql, array($_POST['title'], $_POST['description'], $_POST['pic'], $_POST['link'], $_POST['waktu'], $_POST['target'], $_POST['tls'], $_POST['color'], $_POST['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }


    /*Delete event */

    public function deleteEvent()
    {

        $sql = "DELETE FROM rencana WHERE id = ?";
        $this->db->query($sql, array($_GET['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /*Update  event */

    public function dragUpdateEvent()
    {
        //$date=date('Y-m-d h:i:s',strtotime($_POST['date']));

        $sql = "UPDATE rencana SET  rencana.start = ? ,rencana.end = ?  WHERE id = ?";
        $this->db->query($sql, array($_POST['start'], $_POST['end'], $_POST['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}

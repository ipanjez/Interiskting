<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jad_model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    public function getEvents()
    {

        $sql = "SELECT * FROM events WHERE events.start BETWEEN ? AND ? ORDER BY events.start ASC";
        return $this->db->query($sql, array($_GET['start'], $_GET['end']))->result();
    }

    /*Create new events */

    public function addEvent()
    {

         $sql = "INSERT INTO events (title,events.start,events.end,description,pic,target,tls,color) VALUES (?,?,?,?,?,?,?,?)";
        $this->db->query($sql, array($_POST['title'], $_POST['start'], $_POST['end'], $_POST['description'], $_POST['pic'], $_POST['target'], $_POST['tls'], $_POST['color']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /*Update  event */

    public function updateEvent()
    {

        $sql = "UPDATE events SET title = ?, description = ?, pic = ?, target = ?, tls = ?, color = ? WHERE id = ?";
        $this->db->query($sql, array($_POST['title'], $_POST['description'], $_POST['pic'], $_POST['target'], $_POST['tls'], $_POST['color'], $_POST['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }


    /*Delete event */

    public function deleteEvent()
    {

        $sql = "DELETE FROM events WHERE id = ?";
        $this->db->query($sql, array($_GET['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /*Update  event */

    public function dragUpdateEvent()
    {
        //$date=date('Y-m-d h:i:s',strtotime($_POST['date']));

        $sql = "UPDATE events SET  events.start = ? ,events.end = ?  WHERE id = ?";
        $this->db->query($sql, array($_POST['start'], $_POST['end'], $_POST['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}

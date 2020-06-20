<?php
defined('BASEPATH') or exit('No direct script access allowed');

class user_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function getUser($id)
    {
        $query = $this->db->get_where('user', ['id' => $id]);
        return $query->row_array();
    }

    public function getUserByEmail($email)
    {
        $qr = "SELECT * FROM user WHERE email = '$email'";
        $result = $this->db->query($qr)->row_array();
        return $result;
    }

    public function activate($data, $id)
    {
        $sql = "UPDATE user SET is_active = ?  WHERE id = ? ";
        $hsl = $this->db->query($sql, array($data, $id));
    }

    public function changepass($password, $email)
    {
        $qr = "UPDATE user SET password = ? WHERE email = ?";
        $result = $this->db->query($qr, array($password, $email));
    }
}

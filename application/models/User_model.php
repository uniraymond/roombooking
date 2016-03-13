<?php
/**
 * Created by PhpStorm.
 * User: Raymond
 * Date: 2016-03-12
 * Time: 10:06 AM
 */

class User_model extends CI_Model {
    public $id;
    public $fname;
    public $lname;
    public $group;

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getAllUsers()
    {
        $query = $this->db->get('user');
        return $query->result();
    }

    public function getKeyUsers($keyword){
        $query = $this->db->query(
            'SELECT id, fname, lname, student_id
             FROM user
             WHERE fname LIKE "%'.$keyword.'%"
             OR lname LIKE "%'.$keyword.'%"
             OR student_id LIKE "%'.$keyword.'%"'
        );

        return $query->result();
    }
}
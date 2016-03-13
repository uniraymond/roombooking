<?php
/**
 * Created by PhpStorm.
 * User: Raymond
 * Date: 2016-03-13
 * Time: 8:32 AM
 */

class Room_model extends CI_Model{

    public $id;
    public $room_name;

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getAllRooms()
    {
        $query = $this->db->get('room');

        return $query->result();
    }

    public function getUserRooms($user_id){
        $this->db->select('group');
        $this->db->where('id', $user_id);
        $q = $this->db->get('user');
        $data = $q->result_array();
        $group = $data[0]['group'];

        $groupRang = 'student';
        switch ($group) {
            case 'student':
                $groupRang = 'room_type = "student"';
                break;
            case 'staff':
                $groupRang = 'room_type IN ("student", "staff")';
                break;
            case 'professor':
                $groupRang = 'room_type IN ("student", "staff", "professor")';
                break;
            case 'others':
                $groupRang = 'room_type IN ("student", "staff", "professor", "others")';
                break;
        }

        $query = $this->db->query(
            'SELECT id, room_name
             FROM room
             WHERE '. $groupRang);

        return $query->result();
    }

    public function getKeywordUserRooms($keyword, $user_id){
        $this->db->select('group');
        $this->db->where('id', $user_id);
        $q = $this->db->get('user');
        $data = $q->result_array();
        $group = $data[0]['group'];

        $groupRang = 'student';
        switch ($group) {
            case 'student':
                $groupRang = 'room_type = "student"';
                break;
            case 'staff':
                $groupRang = 'room_type IN ("student", "staff")';
                break;
            case 'professor':
                $groupRang = 'room_type IN ("student", "staff", "professor")';
                break;
            case 'others':
                $groupRang = 'room_type IN ("student", "staff", "professor", "others")';
                break;
        }

        $query = $this->db->query(
            'SELECT *
             FROM room
             WHERE (room_name LIKE "%'.$keyword.'%"
             OR room_location LIKE "%'.$keyword.'%")
            AND ' . $groupRang
        );

        return $query->result();
    }

    public function getMaxRoomHours($room_id, $startTime){
        $now = new DateTime;
        $today = $now->format('d-m-Y');

        $this->db->select('room_open_time');
        $this->db->where('id', $room_id);
        $q = $this->db->get('room');

        $data = $q->result_array();
        $openHour = $data[0]['room_open_time'];
        $ohArray = explode('-', $openHour);

        //get the room close hour
        $closeHour = $ohArray[1];

        //object selected room time
        $d1 = new DateTime($startTime);
        //object the room close hour
        $d2 = new DateTime($today.' '.$closeHour.':0:0');

        if ($d2 > $d1) {
            $diff = $d2->diff($d1)->h;
        } else {
            $diff = 0;
        }
        return $diff;
    }
}
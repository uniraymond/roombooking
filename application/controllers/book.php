<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('room_model');
		$this->load->helper('url_helper');
	}
	public function index()
	{
		$this->load->model('user_model');
		var_dump($this->user_model->getAllUsers());
		$this->load->view('booking');
	}

	public function load()
	{
		$this->load->view('welcome_message');
	}

    public function formsuccess(){
        $this->load->view('formsuccess');
    }

    public function getKeyusers() {
//        $users = $this->user_model->getAllUsers();
        $keyword = $this->input->get('term');
        $users = $this->user_model->getKeyUsers($keyword);
        $return_array = array();
        foreach ($users as $user) {
            $allusers['id'] = $user->id;
            $allusers['value'] = $user->fname . ' ' . $user->lname . ' - ' . $user->student_id;
            array_push($return_array, $allusers);
        }
        echo json_encode($return_array);
    }

    public function getAllusers() {
        $users = $this->user_model->getAllUsers();
//        $users = $this->user_model->getKeyUsers();
        $return_array = array();
        foreach ($users as $user) {
            $allusers['id'] = $user->id;
            $allusers['value'] = $user->fname . ' ' . $user->lname . ' - ' . $user->student_id;
            array_push($return_array, $allusers);
        }
        echo json_encode($return_array);
    }

    public function getAllrooms() {
        $rooms = $this->room_model->getAllRooms();
        $return_array = array();
        foreach ($rooms as $room) {
            $allrooms['id'] = $room->id;
            $allrooms['value'] = $room->room_name;
            array_push($return_array, $allrooms);
        }

        echo json_encode($return_array);
    }

    public function getUserrooms($user_id) {
        $user_id = $this->room_model->getKeyuserRooms();
        $rooms = $this->room_model->getUserRooms($user_id);
        $return_array = array();
        foreach ($rooms as $room) {
            $allrooms['id'] = $room->id;
            $allrooms['value'] = $room->room_name;
            array_push($return_array, $allrooms);
        }

        echo json_encode($return_array);
    }

    public function getKeywordUserRooms($user_id) {
        $keyword = $this->input->get('term');
        $rooms = $this->room_model->getKeywordUserRooms( $keyword, $user_id);
//        $rooms = $this->room_model->getUserRooms($user_id);
        $return_array = array();
        foreach ($rooms as $room) {
            $allrooms['id'] = $room->id;
            $allrooms['value'] = $room->room_name . " - " . $room->room_location;
            array_push($return_array, $allrooms);
        }

        echo json_encode($return_array);
    }

    public function getMaxHour(){
        $room_id = $this->input->post('roomNum');
        $startTime = $this->input->post('bookHour');
        $roomMaxHour = $this->room_model->getMaxRoomHours($room_id, $startTime);
        echo json_encode(array('bookUni'=>$roomMaxHour));
    }

    public function booking()
    {
        $this->load->helper(array('form'));
        $this->load->library('form_validation');

        $data['title'] = 'Room Booking';
        $users = $this->user_model->getAllUsers();
        $u[0] = 'Select a user';
        $allusers = '';
        foreach ($users as $user) {
            $u[$user->id] = $user->fname.' '.$user->lname;
            $allusers .= '"'.$user->fname.' '.$user->lname.' - '.$user->student_id.'", ';
        }

        $rooms = $this->room_model->getAllRooms();
        $r[0] = 'Select a room';
        foreach ($rooms as $room) {
            $r[$room->id] = $room->room_name;
            $o[$room->id] = $room->room_open_time;
        }

        $data['users'] = $u;
        $data['rooms'] = $r;
        $data['open_hours'] = $o;
        $data['allusers'] = $allusers;

        $this->form_validation->set_rules('user', 'user', 'required');
        $this->form_validation->set_rules('room', 'room', 'required');

        $this->load->view('header', $data);

        if ($this->form_validation->run() == False) {
            $this->load->view('booking', $data);
        } else {
            $this->load->view('formsuccess');
        }
        $this->load->view('footer');
    }

    public function addBook(){
        $bookdate = $this->input->post('bookdate');
        $booktime = $this->input->post('booktime');
        $bookduration = $this->input->post('bookuni');
        $book_start = new DateTime($bookdate.' '.$booktime);
        $book_finish = $book_start;

        $data = array(
            'user_id' => $this->input->post('hideUser'),
            'room_id' => $this->input->post('hideRoom'),
            'book_start_date_time' =>  $book_start->format('Y-m-d H:i:s'),
            //caculate finish time
            'book_finish_date_time' => $book_finish->add(new DateInterval("PT{$bookduration}H"))->format('Y-m-d H:i:s'),
            'book_duration' => $bookduration
        );
        $new_book_record = $this->db->insert('book', $data);
        if ($new_book_record) {
            $id = $this->db->insert_id();
            $q = $this->db->get_where('book', array('id'=>$id));
            echo json_encode($q->row());
        }
        return;
    }
}
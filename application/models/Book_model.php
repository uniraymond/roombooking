<?php
    class Book_model extends CI_Model
    {
        public $id;
        public $user_id;
        public $room_id;
        public $book_start_date_time;
        public $book_finish_date_time;
        public $book_duration;

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        public function getAllBooks()
        {
            $query = $this->db->get('book');

            return $query->result();
        }

        public function checkBookTime($start, $room_id)
        {
            $query = $this->db->query('SELECT * FROM book WHERE book_start_date_time <= "'.$start.'" AND book_finish_date_time >= "'. $start . '" AND room_id ='.$room_id);

            return $query->result();
        }

        public function deleteBook($book_id){
            return $this->db->delete('book', array('id'=>$book_id));
        }
    }
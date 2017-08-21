<?php

class Sds_model extends CI_Model
{



    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }


    public function getSDS(){
        $query = $this->db->get('sds');
        $results = $query->result_array();
        return $results;
    }
}

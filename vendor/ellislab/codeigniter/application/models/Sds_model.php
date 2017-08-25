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
        $this->db->order_by('id');
        $query = $this->db->get('sds');

        $results = $query->result_array();
        return $results;
    }

    public function addSDS($record){


        $this->db->insert('sds', $record);
        return $this->db->insert_id();
    }

    public function updateSDS($record, $id){


        return $this->db->update('sds', $record, array('id'=>$id));
    }
}

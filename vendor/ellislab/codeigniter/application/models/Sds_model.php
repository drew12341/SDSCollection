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
        $expire_time = strtotime("+6 year");
        $before = date('Y-m-d', $expire_time);

        $this->db->join('users', 'users.id=sds.uploader');
        $this->db->where('published <',$before);
        $this->db->order_by('id');
        $query = $this->db->get('sds');

        $results = $query->result_array();
        $now = strtotime("now");
        foreach ($results as &$result){
            if(strtotime($result['expiry']) < $now){
                $result['expired'] = true;
            }
            else{
                $result['expired'] = false;
            }
        }
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

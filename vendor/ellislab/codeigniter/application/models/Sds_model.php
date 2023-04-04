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
        $this->db->select('users.username, users.email, users.first_name, users.last_name, sds.substance_name, sds.uploader,sds.vendor,sds.published, sds.expiry, sds.expiry, sds.cas, sds.filename, users.id as user_id, sds.id as sds_id');
        $this->db->where('published <',$before);
        $this->db->order_by('sds.id', "desc");
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

    public function getSingleSDS($id){

        $this->db->where('id',$id);
        $query = $this->db->get('sds');

        $results = $query->result_array();
        return $results[0];
    }

    public function addSDS($record){


        $this->db->insert('sds', $record);
        return $this->db->insert_id();
    }

    public function updateSDS($record){
        $id = $record['id'];
        unset($record['id']);
        $this->db->update('sds', $record, array('id'=>$id));
        return $id;
    }
}

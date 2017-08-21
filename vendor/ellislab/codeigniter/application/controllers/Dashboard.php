<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Auth_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->output->set_template('default');
        $this->load->model('task_model');
        $this->load->model('Sds_model');
    }

    function index(){
        $data = array('sds'=>$this->Sds_model->getSDS());
        $this->load->view('dashboard/index_view', $data);
    }
}
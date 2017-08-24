<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sds extends CI_Controller
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
        $this->load->view('sds/index_view', $data);
    }

    function newSds(){
        $this->load->library('form_builder');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('substance_name', 'Substance Name','trim|required');
        $this->form_validation->set_rules('vendor', 'Vendor','trim|required');


        if($this->form_validation->run()===FALSE)
        {
            $this->load->view('sds/new_view');
        }
        else
        {
            $record = $this->input->post();
            unset($record['submit']);

            $config['upload_path'] = APPPATH.'../tmp/';
            $config['allowed_types'] = 'pdf|png';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload())
            {
                $error = array('error' => $this->upload->display_errors());

                $_SESSION['aa_message'] = $this->upload->display_errors();
                $this->load->view('sds/new_view');
            }
            else
            {
                $data = array('upload_data' => $this->upload->data('orig_name'));
                $filename = $this->upload->data('orig_name');

                $url = 'https://cloudstor.aarnet.edu.au/plus/public.php/webdav/'.$filename;
                $client = new Guzzle\Http\Client();
                $client->setDefaultOption('auth', array(
                    'lRqlE8FPZr2HFk1',''
                ));
                $request = $client->put($url);
                $request->setBody(fopen($this->upload->data('full_path'), 'r'));
                //echo $request;
                $res = $request->send();
                //echo $res;
                $_SESSION['aa_message'] = $res;
                $this->load->view('sds/new_view', $data);

            }

//            $_SESSION['aa_message'] = 'Sds Added';
//            $this->session->mark_as_flash('aa_message');
//
//
//            $this->load->view('sds/new_view');
        }
    }
}
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

    public function cas_check($str)
    {
        if(preg_match('/^[a-zA-Z0-9]{1,7}-[a-zA-Z0-9]{2}-[a-zA-Z0-9]{1}+$/', $str ) )
        {
            //Split by '-'
            $parts = explode('-',$str);

            //Then separate into parts
            $prefix = $parts[0];
            $mid = $parts[1];
            $checksum = $parts[2];

            //split into individual digits and then reverse order to make calculation easier
            $mid_r = array_reverse(str_split($mid));
            $prefix_r = array_reverse(str_split($prefix));


            $total = 0;
            //essentially working backwards here - doing the 1*a, 2*b
            for($i = 0; $i< count($mid_r); $i++){
                $total += ($i + 1) * $mid_r[$i];
            }
            //now doing 3*c, 4*d etc until we run out of digits
            for($i = 0; $i< count($prefix_r); $i++){
                $total += ($i + 3) * $prefix_r[$i];
            }
            //Check against mod 10
            if ($total % 10 != $checksum){
                $this->form_validation->set_message('cas_check', 'The {field} field does not match checksum - please check that it is entered correctly');
                return FALSE;
            }

            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('cas_check', 'The {field} field must be in the format (1-7 Digits)-(2 Digits)-(1 Digit)');
            return FALSE;
        }
    }

    function newSds($mode= 0){
        if(!$this->ion_auth->logged_in()){
            redirect('/');
        }

        if($mode == 1){
            $_SESSION['aa_message'] = null;
        }
        $this->load->library('form_builder');
        $this->load->library('form_validation');
        $this->config->load('SDS_config');

        $this->form_validation->set_rules('substance_name', 'Substance Name','trim|required');
        $this->form_validation->set_rules('userfile', 'File','trim|xss_clean');
        $this->form_validation->set_rules('cas', 'Cas', array('required','callback_cas_check'));
        $this->form_validation->set_rules('vendor', 'Vendor','trim|required');
        $this->form_validation->set_rules('published', 'Published','trim|required');


        if($this->form_validation->run()===FALSE)
        {
            $data = array();
            $this->load->view('sds/new_view', $data);
        }
        else
        {
            $record = $this->input->post();

            //fix expiry date
            $record['published'] = str_replace('/', '-', $record['published']);
            $record['published'] = date("Y-m-d", strtotime($record['published']));

            $record['expiry'] = date('Y-m-d',strtotime($record['published'] . " + 5 year"));

            unset($record['submit']);
            $record['uploader'] = $this->ion_auth->user()->row()->id;

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
                $id = $this->Sds_model->addSDS($record);


                $filename = $id."_".$this->upload->data('orig_name');

                $url = $this->config->item('sds_url').$filename;
                $client = new Guzzle\Http\Client();
                $client->setDefaultOption('auth', array(
                    $this->config->item('foldername'),$this->config->item('password')
                ));
                $request = $client->put($url);
                $request->setBody(fopen($this->upload->data('full_path'), 'r'));
                //echo $request;
                $res = $request->send();

                $link = $this->config->item('sds_download_url').$this->config->item('foldername').'/download?path=%2F&files='.$filename;
                $record['link'] = $link;
                $record['id'] = $id;
                $this->Sds_model->updateSDS($record);

                $_SESSION['aa_message'] = 'SDS Successfully Added';
                $data = array('record'=>$record);
                $this->load->view('sds/new_view', $data);

            }
        }
    }

    function editSds($id){
        if(!$this->ion_auth->logged_in()){
            redirect('/');
        }


        $this->load->library('form_builder');
        $this->load->library('form_validation');
        $this->config->load('SDS_config');

        $this->form_validation->set_rules('substance_name', 'Substance Name','trim|required');
        $this->form_validation->set_rules('userfile', 'File','trim|xss_clean');
        $this->form_validation->set_rules('cas', 'Cas', array('required','callback_cas_check'));
        $this->form_validation->set_rules('vendor', 'Vendor','trim|required');
        $this->form_validation->set_rules('published', 'Published','trim|required');


        if($this->form_validation->run()===FALSE)
        {
            $_SESSION['edit_message'] = null;
            $data = array('dataSet'=>$this->Sds_model->getSingleSDS($id));
            $this->load->view('sds/edit_view', $data);
        }
        else
        {
            $record = $this->input->post();

            //fix expiry date
            $record['published'] = str_replace('/', '-', $record['published']);
            $record['published'] = date("Y-m-d", strtotime($record['published']));

            $record['expiry'] = date('Y-m-d',strtotime($record['published'] . " + 5 year"));

            unset($record['submit']);
            //$record['uploader'] = $this->ion_auth->user()->row()->id;

            $config['upload_path'] = APPPATH.'../tmp/';
            $config['allowed_types'] = 'pdf|png';

            $this->load->library('upload', $config);

            //if the file is being replaced (i.e., field not empty) then upload and link
            if (isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])) {
                if (!$this->upload->do_upload()) {

                    $_SESSION['edit_message'] = $this->upload->display_errors();
                    $data = array('dataSet'=>$record);
                    $this->load->view('sds/edit_view', $data);
                } else {



                    $filename = $record['id'] . "_" . $this->upload->data('orig_name');

                    $url = $this->config->item('sds_url') . $filename;
                    $client = new Guzzle\Http\Client();
                    $client->setDefaultOption('auth', array(
                        $this->config->item('foldername'), $this->config->item('password')
                    ));
                    $request = $client->put($url);
                    $request->setBody(fopen($this->upload->data('full_path'), 'r'));
                    //echo $request;
                    $res = $request->send();

                    $link = $this->config->item('sds_download_url') . $this->config->item('foldername') . '/download?path=%2F&files=' . $filename;
                    $record['link'] = $link;

                    $this->Sds_model->updateSDS($record);

                    $_SESSION['edit_message'] = 'SDS Successfully Updated';
                    $data = array('dataSet'=>$record);
                    $this->load->view('sds/edit_view', $data);

                }
            }
            else{
                $this->Sds_model->updateSDS($record);
                $_SESSION['edit_message'] = 'SDS Successfully Updated 2';
                $data = array('dataSet'=>$record);
                $this->load->view('sds/edit_view', $data);
            }
        }
    }

    public function delete_sds($id){
        $this->db->delete('sds', array('id' => $id));
        $_SESSION['sds_message'] = 'SDS has been deleted';
        $this->session->mark_as_flash('sds_message');
        redirect('Sds');

    }

}
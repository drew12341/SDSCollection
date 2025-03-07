<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->config->load('SDS_config');
        $this->output->set_template('default');
    }

    public function index()
    {
        if($this->ion_auth->is_admin()===FALSE)
        {
            redirect('/');
        }

        $data = array('dataSet'=>$this->ion_auth->getUsers());

        $this->load->view('user/index_view', $data);
    }

    public function profile(){

        $data = array();

        $this->load->view('user/profile_view', $data);
    }

    public function changepassword($userid){
        if($userid != $this->ion_auth->user()->row()->id && $this->ion_auth->is_admin()===FALSE)
        {
            redirect('/');
        }

        $this->load->library('form_builder');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('password','Password','trim|min_length[8]|max_length[20]|required');
        $this->form_validation->set_rules('confirm_password','Confirm password','trim|matches[password]|required');
        if($this->form_validation->run()===FALSE)
        {
            unset($_SESSION['edit_message']);
            $groups = $this->ion_auth->listGroups();
            $data = array('dataSet'=>$this->ion_auth->getUser($userid),
                'groups'=>$groups);
            $this->load->view('user/edit_view', $data);
        }
        else
        {
            unset($_SESSION['edit_message']);
            $id = $this->input->post('id');

            $dataSet['password'] = $this->input->post('password');

            $this->load->library('ion_auth');
            if($this->ion_auth->update($id,$dataSet))
            {
                $_SESSION['edit_message'] = 'Password has been updated.';
            }
            else
            {
                $_SESSION['edit_message'] = $this->ion_auth->errors();
            }

            $groups = $this->ion_auth->listGroups();

            $dataSet['user_id'] = $id;
            $data = array('dataSet'=>$this->ion_auth->getUser($id),
                'groups'=>$groups);
            $this->load->view('user/edit_view', $data);
        }
    }

    public function edit($userid)
    {
        if($userid != $this->ion_auth->user()->row()->id && $this->ion_auth->is_admin()===FALSE)
        {
            redirect('/');
        }

        $this->load->library('form_builder');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('first_name', 'First name','trim|required');
        $this->form_validation->set_rules('last_name', 'Last name','trim|required');

        //$this->form_validation->set_rules('email','Email','trim|valid_email|required');
        $this->form_validation->set_rules('email','Email', array('trim','valid_email','required','callback_email_check'));

        if($this->form_validation->run()===FALSE)
        {
            unset($_SESSION['edit_message']);
            $this->load->helper('form');
            $data = array('dataSet'=>$this->ion_auth->getUser($userid),
                'groups'=>$this->ion_auth->listGroups());
            $this->load->view('user/edit_view', $data);
        }
        else
        {
            unset($_SESSION['edit_message']);
            $id = $this->input->post('id');

            $dataSet['first_name'] = $this->input->post('first_name');
            $dataSet['last_name'] = $this->input->post('last_name');

            $dataSet['email'] = strtolower($this->input->post('email'));
            $this->load->library('ion_auth');



            if($this->ion_auth->update($id,$dataSet))
            {
                $_SESSION['edit_message'] = 'User has been updated.';
            }
            else
            {
                $_SESSION['edit_message'] = $this->ion_auth->errors();
            }
            //fix up groups
            //Set group if checkbox is checked
            $groups = $this->ion_auth->listGroups();
            $ids = array_keys($groups);

            $edit = $this->input->post('edit');

            if (isset($edit)) {
                $this->ion_auth->remove_from_group(false, $id);
                $this->ion_auth->add_to_group(3, $id);
            } //else remove from group
            else {
                $this->ion_auth->remove_from_group(false, $id);
                $this->ion_auth->add_to_group(2, $id);
            }



            $data = array('dataSet'=>$this->ion_auth->getUser($id),
                'groups'=>$groups);
            //$this->load->view('user/edit_view', $data);
            redirect(site_url('User/edit/').$id);
        }


    }

    public function delete_user($userid){
            $this->ion_auth->delete_user($userid);
            $_SESSION['ar_message'] = 'User has been deleted';
            $this->session->mark_as_flash('ar_message');
            redirect('User');

    }


    function email_check($str)
    {
        $parts = explode('@',$str);

        if(isset($parts[1]) && $parts[1] == 'uts.edu.au') return true;
		if(isset($parts[1]) && $parts[1] == 'student.uts.edu.au') return true;

        //if (stristr($str,'@uts.edu.au') !== false) return true;


        $this->form_validation->set_message('email_check', 'Email must have a @uts.edu.au or @student.uts.edu.au suffix');
        return FALSE;
    }

    public function register()
    {

        $this->load->library('form_builder');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First name','trim|required');
        $this->form_validation->set_rules('last_name', 'Last name','trim|required');
        $this->form_validation->set_rules('email','Email', array('trim','valid_email','required','is_unique[users.email]','callback_email_check'));
        $this->form_validation->set_rules('password','Password','trim|min_length[8]|max_length[20]|required');
        $this->form_validation->set_rules('confirm_password','Confirm password','trim|matches[password]|required');
        //$this->form_validation->set_rules('email', 'Email', 'callback_email_check');

        if($this->form_validation->run()===FALSE)
        {
            $this->load->helper('form');
            $this->load->view('user/register_view');
        }
        else
        {
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');

            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'active'=>true,

            );

            $this->load->library('ion_auth');
            $group = array('2');
            $userid = $this->ion_auth->register($email,$password,$email,$additional_data, $group);
            if($userid)
            {


                $_SESSION['register_message'] = 'The user: '.$first_name.' '.$last_name .' has been created. Activation Email sent - please check your inbox or spam.';
                $this->session->mark_as_flash('register_message');

                //redirect('user/edit/'.$userid);
                $this->load->view('user/register_view');
                //Auto login after register
//                if ($this->ion_auth->login($email, $password, True))
//                {
//                    redirect('sds');
//                }
//                else{
//                    redirect('user/login');
//                }
            }
            else
            {
                $_SESSION['register_message'] = $this->ion_auth->errors();
                $this->session->mark_as_flash('register_message');
                redirect('user/register');

            }
        }
    }



    public function login()
    {
        $this->load->library('form_builder');
        $this->data['title'] = "Login";

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->helper('form');
            $this->load->view('user/login_view');
            //$this->render('user/login_view');
        }
        else
        {
            $remember = (bool) $this->input->post('remember');
		//$remember = true;
            if ($this->ion_auth->login(strtolower($this->input->post('email')), $this->input->post('password'), $remember))
            {
                redirect('sds');
            }
            else
            {
                $_SESSION['auth_message'] = $this->ion_auth->errors();
                $this->session->mark_as_flash('auth_message');
                redirect('user/login');
            }
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('user/login');
    }
}
<?php

class Mail_model extends CI_Model
{
    private $defaultFromMail = 'safetyandwellbeing@uts.edu.au';
    private $defaultFromName = 'iAuditor Action Tracker';
    private $defaultCompletionSubject = 'iAuditor Action Tracker - Past Completion Date';
    private $defaultAssignedSubject = 'iAuditor Action Tracker - Assigned Item';
	private $defaultNotifySubject = 'iAuditor Action Tracker - Inspection Receipt';


    function __construct() {
        parent::__construct();

        $this->load->model('Ion_auth_model');

        $this->load->library('email');

				$config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'postoffice.uts.edu.au',
        'smtp_port' => 25,
        'smtp_user' => '',
        'smtp_pass' => '',
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
        );

		$this->email->initialize($config);

        /* Alternate setup if default mail doesn't work
        $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_port' => 465,
        'smtp_user' => 'my-email@gmail.com',
        'smtp_pass' => '***',
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
        );

        $this->email->initialize($config);
        */
    }

}
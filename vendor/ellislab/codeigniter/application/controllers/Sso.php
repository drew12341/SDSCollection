<?php



class Sso extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth','form_validation'));
        $this->load->helper(array('url','language'));
        $this->output->set_template('default');
        $this->lang->load('auth');
    }

    public function index()
    {
        require_once(__DIR__."/../../../../phpSAMLsampleApp/lib/bootstrap.php");

        if (!PHPSAMLProcessor::self()->isAuthenticated()) {
            PHPSAMLProcessor::self()->init();
        }
        if(PHPSAMLProcessor::self()->isAuthenticated()) {
            $userID = PHPSAMLProcessor::self()->getAuthenticatedUserId();
            $userID = strtolower($userID);
            echo $userID;

            $user = $this->ion_auth->getUserByIdentity($userID);


            if(!$user){
                //TODO remove this for live
                //$userID = 'test.user@uts.edu.au';

                $userdata = [];
                $parts = explode("@",$userID);
                $username = $parts[0];
                $uname_parts = explode(".", $username);
                $userdata['first_name'] = ucwords($uname_parts[0]);
                if(isset($uname_parts[1])){ $userdata['last_name'] = ucwords($uname_parts[1]);}
                $password = '3f364b2ae70e63afbd303699618bdce6';

                $additional_data = array(
                    'first_name' => $userdata['first_name'],
                    'last_name' => $userdata['last_name'],
                    'active'=>true,

                );

                $this->load->library('ion_auth');
                $group = array('2');
                $userid = $this->ion_auth->register($userID, $password, $userID, $additional_data, $group);

                if($userid){
                    //good
                    echo "user saved\n<br>";
                }
                else{
                    echo 'errors:\n<br>';

                }
                $user = $this->ion_auth->getUserByIdentity($userID);

            }
            //echo json_encode($user);
            $loggd_in = $this->ion_auth->login_by_id($user['user_id']);

            return redirect('/');
        }
        //return redirect('/');
    }

    public function bypasssso(){
        $loggd_in = $this->ion_auth->login_by_id(4);
        return redirect('/');
    }
    //--------------------------------------------------------------------

}


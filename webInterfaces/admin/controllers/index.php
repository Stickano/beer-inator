<?php


class IndexController{

    private $sessions;
    private $token;
    private $curl;
    
    public function __construct(Session $sessions, string $token, Curl $curl){
        $this->sessions = $sessions;
        $this->token = $token;
        $this->curl = $curl;
    }    

    /**
     * This function will call the REST interface and try to login
     * @return  Redirects, either to CMS if success, or same page if failed.
     */
    public function login(){
        $uname = $_POST['uname'];
        $upass = $_POST['upass'];

        # If empty values, send back with error
        if (empty($uname) || empty($upass)){
            $this->sessions->set('error', 'Begge felter skal udfyldes');
            header("location:".$_SERVER['PHP_SELF']);
            die;
        }

        # Hash the password
        #$upass = password_hash($upass, PASSWORD_BCRYPT);
        $upass = hash('sha256', $upass);

        # Prepare the POST
        $data = ['uname' => $uname, 'password' => $upass];

        # Call the REST login method
        $this->curl->post($data);
        $this->curl->showError(true);
        $login = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/profile/login/RtbDihnLR5D8Y");

        # If login failed
        if ($login == null){
            $this->sessions->set('error', 'Login mislykket');
            header("location:".$_SERVER['PHP_SELF']);   
            die;
        }
        
        $this->sessions->set('loggedId', $login['id']);
        $this->sessions->set('loggedRole', $login['role']);
        $this->sessions->set('pwHash', $login['password']);
        if ($login['role'] == 2)
            header("location:?cms");
        header("lcoation:?buyer");
    }

    /**
     * This method will return errors to the view, if failed logins
     * @return NULL|String Nothing, or the error message
     */
    public function getError(){
        if($this->sessions->isset('error')){
            $error = $this->sessions->get('error');
            $this->sessions->unset('error');
            return $error;
        }
    }
}

?>
<?php

class PasswordController{

    private $sessions;
    private $token;
    private $curl;

    public function __construct(Session $sessions, string $token, Curl $curl){
        $this->sessions = $sessions;
        $this->token = $token;
        $this->curl = $curl;

        self::isLoggedIn();
    }

    private function isLoggedIn(){
        if (!$this->sessions->isset("loggedId")){
            header("location:index.php");
            die;
        }
    }

    public function changePassword(){
        # Confirm that new password 1 and 2 matched
        if ($_POST['new1'] != $_POST['new2']){
            $this->sessions->set('error', 'Dit nye kodeord stemte ikke overens.');
            header("location:?password");
            die;
        }

        # Hash the new password
        $pw = hash('sha256', $_POST['new1']);

        # Pack the data
        $data = ['id' => $this->sessions->get('loggedId'), 'password' => $pw];
        $this->curl->put($data);

        # Make the request
        $result = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/profile/update/password/".$this->token);


    }
}
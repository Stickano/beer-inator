<?php

require_once 'models/random.php';

class CmsController{

    private $sessions;
    private $token;
    private $curl;
    private $random;

    private $fridgeMin;
    private $fridgeMax;
    private $notifyMin;

    private $allProfiles;

    public function __construct(Session $sessions, string $token, Curl $curl){
        $this->sessions = $sessions;
        self::isLoggedIn();
        $this->token  = $token;
        $this->curl   = $curl;
        $this->random = new Random();

        $this->fridgeMin   = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/settings/read/fridgemin");
        $this->fridgeMax   = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/settings/read/fridgemax");
        $this->notifyMin   = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/settings/read/notifymin");
        $this->allProfiles = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/profile/read/".$this->token);

    }

    /**
     * Confirms that the user is logged in
     * @return boolean True/False
     */
    private function isLoggedIn(){
        if (!$this->sessions->isset("loggedId")){
            $this->sessions->set('error', 'Glem det.');
            header("location:index.php");
            die;
        }

        self::isAdmin();
    }

    /**
     * Confirms that the user has administrative privledges
     * @return boolean True/False
     */
    private function isAdmin(){
        if ($this->sessions->get('loggedRole') != 2){
            $this->sessions->destroy();
            $this->sessions->set('error', 'Glem det.');
            header("location:index.php");
            die;
        }
    }

    /**
     *      Get and Update methods for the 'settings' panel in the database
     *      ===============================================================
     *
     */
    public function getFridgeMin(){
        return $this->fridgeMin;
    }

    public function getFridgeMax(){
        return $this->fridgeMax;
    }

    public function getNotifyMin(){
        return $this->notifyMin;
    }

    public function updateMinFridge(){
        $value = $_POST['value'];
        $return = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/settings/update/fridgemin/".$this->token."/".$value);
        if ($return == "-1")
            $this->sessions->set('error', 'Noget gik galt. Prøv igen.');
        else
            $this->sessions->set('message', 'Opdateret');
        header("location:index.php?cms");
    }

    public function updateMaxFridge(){
        $value = $_POST['value'];
        $return = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/settings/update/fridgemax/".$this->token."/".$value);
        if ($return == "-1")
            $this->sessions->set('error', 'Noget gik galt. Prøv igen.');
        else
            $this->sessions->set('message', 'Opdateret');
        header("location:index.php?cms");
    }

    public function updateMinNotify(){
        $value = $_POST['value'];
        $return = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/settings/update/notifymin/".$this->token."/".$value);
        if ($return == "-1")
            $this->sessions->set('error', 'Noget gik galt. Prøv igen.');
        else
            $this->sessions->set('message', 'Opdateret');
        header("location:index.php?cms");
    }
    /**
     *      End of Get and Update methods for the 'settings' panel in the database
     *      ======================================================================
     *
     */
    

    /**
     * Method to create a new profile in the DB
     * @return  Redirects back with an message (success/fail)
     */
    public function createProfile(){
        $uname = $_POST['uname'];
        $role = $_POST['role'];
        $fullname = $_POST['fullname'];

        $pw = $this->random->random();
        $pwHash = hash('sha256', $pw);

        $data = ['uname' => $uname, 'password' => $pwHash, 'fullname' => $fullname, 'role' => $role];
        $this->curl->post($data);
        $return = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/profile/create/".$this->token);

        if ($return != "NULL")
            $this->sessions->set('message', "OBS!: Adgangskoden for den nye profile er: ".$pw);
        else
            $this->sessions->set('error', 'Noget gik galt. Prøv igen.');

        header("location:index.php?cms");
    }

    /**
     * Returns all the profiles from the database for the view
     * @return array ['id', 'fullname', 'uname', 'role']
     */
    public function getAllProfiles(){
        return $this->allProfiles;
    }

    /**
     * This will reset the password for any user whom might have forgotten theirs.
     * @return string The new password (random)
     */
    public function resetPassword(){
        $id = $_POST['id'];
        $pw = $this->random->random();
        $pwHash = hash('sha256', $pw);

        $data = ['id' => $id, 'password' => $pwHash];
        $this->curl->put($data);
        $return = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/profile/update/password/".$this->token);
        
        if ($return != "NULL")
            $this->sessions->set('message', "OBS!: Adgangskoden er ændret til: ".$pw);
        else
            $this->sessions->set('error', 'Noget gik galt. Prøv igen.');

        header("location:index.php?cms");
    }

    /**
     * This will delete any profiles from the database
     * @return string  Success/Fail message
     */
    public function deleteProfile(){
        $id = $_POST['id'];
        $return = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/profile/delete/".$this->token."/".$id);
        if ($return == "1")
            $this->sessions->set('message', "Profil Slettet");
        else
            $this->sessions->set('error', 'Noget gik galt. Prøv igen.');

        header("location:index.php?cms");
    }
}

?>
<?php

require_once 'models/time.php';

class StatisticsController{

    private $sessions;
    private $token;
    private $curl;
    private $time;

    private $allValues;
    private $viewValues;
    private $fromDate;
    private $toDate;

    public function __construct(Session $sessions, string $token, Curl $curl){
        $this->sessions  = $sessions;
        self::isLoggedIn();
    
        $this->token     = $token;
        $this->curl      = $curl;
        $this->time      = new Time();
        
        $this->viewValues = array();
        $this->allValues  = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/beers/read");
        $this->fromDate   = $this->allValues[sizeof($this->allValues) - 1]['dateTime'];
        $this->fromDate   = substr($this->fromDate, 0, 8);
        $this->toDate     = $this->time->timestamp('year')
                           .$this->time->timestamp('month')
                           .$this->time->timestamp('day');

        self::selectViewValues();
    }

    /**
     * Perform a check, that the user is logged in
     * @return   Dies and redirects if false
     */
    private function isLoggedIn(){
        if (!$this->sessions->isset("loggedId")){
            header("location:index.php");
            die;
        }
    }

    public function formatConverter(int $val){
        $year  = substr($val, 0, 4);
        $month = substr($val, 4, 2);
        $day   = substr($val, 6, 2);
        return $day.'-'.$month.'-'.$year;
    }

    public function getFromDate(){
        return $this->fromDate;
    }

    public function getToDate(){
        return $this->toDate;
    }

    private function selectViewValues(){
        foreach ($this->allValues as $pos) {
            if (substr($pos['dateTime'],0,8) >= $this->fromDate && substr($pos['dateTime'],0,8) <= $this->toDate)
                $this->viewValues[] = $pos;
        }
    }

    public function getViewValues(){
        return $this->viewValues;
    }

    public function getAllValues(){
        return $this->allValues;
    }
}

?>
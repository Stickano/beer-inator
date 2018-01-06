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

    public function tester(){
        foreach ($this->viewValues as $key => $value) {
            $amount[$key] = $value['amount'];
            $time[$key] = substr($value['dateTime'],0,8);
        }
        return array_multisort($time, SORT_ASC, $amount, SORT_DESC, $this->viewValues);


        $ratingsInPosts = array
        (
        array("1",3),
        array("2",5),
        array("2",2),
        array("5",2),
        array("90",1),
        array("5",6),
        array("2",2),
        );
        $arr1 = array_column($ratingsInPosts, 0);
        $p = array_count_values($arr1);
        foreach($p as $key => $value)
        {
          $sum = 0;
          for($i=0; $i < $value; $i++)
          {
            $pos = array_search($key, $arr1);
            $sum += $ratingsInPosts[$pos][1];
            unset($arr1[$pos]);
            unset($ratingsInPosts[$pos]);
          }
          $re[] = array('"'.$key.'"',$sum/$value);
        }
        print_r($re);
        
    }
}

?>
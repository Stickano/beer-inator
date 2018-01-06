<?php

require_once 'models/profile.php';
require_once 'models/curl.php';

class IndexController{

    private $convertedPercentage = 0;
    private $currentPercentage   = 0;
    private $currentValue        = 0;
    private $maxValue            = 0;
    private $notifyMin           = 0;

    private $error;
    private $sessions;
    private $curl;

    public function __construct(Session $sessions){
        $this->sessions   = $sessions;
        $this->curl       = new Curl();
        self::setValues();
    }    

    /**
     * Sets Max/Current value from the database
     */
    private function setValues(){
        # Get and set the current value
        $url = "http://easj-beerinator.azurewebsites.net/Service1.svc/beers/fridge";
        if ($result = $this->curl->curl($url))
            $this->currentValue = $result;

        # Get and set the max fridge value
        $url = "http://easj-beerinator.azurewebsites.net/Service1.svc/settings/read/fridgemax";
        if ($result = $this->curl->curl($url))
            $this->maxValue = $result;

        # Get the notification value
        $url = "http://easj-beerinator.azurewebsites.net/Service1.svc/settings/read/fridgemin";
        if ($result = $this->curl->curl($url))
            $this->notifyMin = $result;

        if ($this->currentValue <= $this->notifyMin)
            $this->sessions->set('error', 'KØLESKABET SKAL FYLDES!');
        
        # If we got return, calculate the values (percentage)
        if ($this->maxValue)
            self::calcValues();
    }

    /**
     * Calculate the current percentage of beers, left in the fridge
     * @return int    Sets $currentPercentage;
     */
    private function calcValues(){
        $calc                    = $this->currentValue / $this->maxValue * 100;
        $this->currentPercentage = round($calc);
        self::convertPercentage();
    }

    /**
     * Convert from the percentage to pixels needed in the view (height:400px;)
     * @return     Sets $convertedPercentage
     */
    private function convertPercentage(){
        $calc                      = $this->currentPercentage / 100;
        $this->convertedPercentage = 330 * $calc;
    }

    /**
     * Returns the converted percentage to pixels for the view
     * @return int   The percentage converted to pixels needed
     */
    public function getConvertedPercentage(){
        return $this->convertedPercentage;
    }

    /**
     * In the view the beer has a height set according to percentage
     * from a top-point - We need to push the beer down the remaining 
     * height, or it would fill from the top-down
     * @return int The calculated remaining height 
     */
    public function getRemainingHeight(){
        return 600 - $this->convertedPercentage;
    }

    /**
     * Returns the calculated percentage for the view
     * @return int   Current percentage
     */
    public function getPercentage(){
        return $this->currentPercentage;
    }

    /**
     * Return the Max/Current value for the view
     * @param  int|integer $val 0=maxValue, 1=currentValue
     * @return array            The requested value
     */
    public function getValues(int $val=0){
        if ($val == 0)
            return $this->maxValue;
        return $this->currentValue;
    }

    /**
     * Gives the bartenders a chance to update the fridge values as well
     * In case something goes wrong I guess
     * @return    Redirects back to same page.
     */
    public function updateFridge(){
        $value = $_POST['current'];
        $token = "RtbDihnLR5D8Y";
        $max = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/beers/update/".$token."/".$value);
        header("location:index.php");
    }
}

?>

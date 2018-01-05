<?php

@session_start();
require_once('resources/session.php');
require_once('resources/token.php');
require_once 'models/curl.php';

final class Singleton {

    private static $instance;
    private static $curl;

    # View and Controller
    public static $page;
    public static $controller;

    # Session Handler
    public static $session;
    public static $message;
    public static $messageColor;

    # Private constructor to ensure it won't be initialized
    private function __construct(){}

    /**
     * This is the initializer for this object
     * It will initialize a Connection and CRUD class
     * It will determine which View to load and its apropriate Controller
     * It will store the page you hit (nowPage), along with storing the previous page (prePage)
     * And it will initialize a couple of typical classes, like meta and time (used in footer) 
     * @return object This (only) instance 
     */
    public static function init(){
        if(!isset(self::$instance)){

            self::$instance = new self();
            self::$curl     = new Curl();
            self::$session  = Session::init();
            
            self::getView();
            self::getController();
            self::checkMessage();
        }

        return self::$instance;
    }

    /**
     * This will determine which page(view) to load
     */
    private static function getView(){
        
        $pages = array();
        foreach (glob("views/*.php") as $file) {
            $page = substr($file, 6, -4);
            $pages[] = $page;
        }

        $search = array_intersect($pages, array_keys($_GET));
        if(in_array('index', $pages)){
            $pos = array_keys($pages, 'index')[0];
            self::$page = $pages[$pos];
        }
        
        if(!empty($search)){
            $search = array_values($search);
            self::$page = $search[0];
        }
    }

    /**
     * This will load the appropriate controller
     */
    public static function getController(){
        require_once('controllers/'.self::$page.'.php');
        $controller = ucfirst(self::$page).'Controller';
        self::$controller = new $controller(self::$session, Token::$token, self::$curl); 
    }

    /**
     * Will generate '&nbsp;'  
     * @param  int    $count The amount of spaces 
     * @return string        The spaces
     */
    public function spaces(int $count){
        $spaces = '';
        for($i=0; $i<$count; $i++){
            $spaces .= '&nbsp;';
        }
        return $spaces;
    }

    public function logout(){
        session_destroy();
        header("location:index.php");
    }

    private static function checkMessage(){
        if (self::$session->isset('error')){
            self::$message = self::$session->get('error');
            self::$messageColor = 'd9534f'; 
            self::$session->unset('error');
        } else if (self::$session->isset('message')){
            self::$message = self::$session->get('message');
            self::$messageColor = '8C9440'; 
            self::$session->unset('message');
        }
    }
}

?>

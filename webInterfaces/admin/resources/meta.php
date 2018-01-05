<?php

    # Singleton
    require_once('resources/singleton.php');
    $singleton = Singleton::init();

    # Shortcut for some commonly used classes
    $controller = $singleton::$controller;
    $session = $singleton::$session;

    echo'<title>Beer Inator CMS</title>';
    echo'<link rel="alternate" href="https://web.site" hreflang="dk" />';
    echo'<meta charset="utf-8" />';
    echo'<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
    echo'<meta name="author" content="John Doe" />';
    echo'<meta name="description" content="A short description of your site" />';
    echo'<meta name="keywords" content="comma, separated, keywords" />';
    echo'<meta name="robots" content="index, follow" />';
    echo'<meta name="viewport" content="width=device-width, initial-scale=0.8" />';
    echo'<link rel="icon" href="media/fav.ico?v=1.1">';

    # Stylesheet(s)
    echo'<link href="css/styles.css" rel="stylesheet">';
    echo'<link href="css/jquery-ui.min.css" rel="stylesheet">';
    echo'<link href="css/jquery-ui.structure.min.css" rel="stylesheet">';
    echo'<link href="css/jquery-ui.theme.min.css" rel="stylesheet">';
    echo'<link href="css/font-awesome.min.css" rel="stylesheet">';
    
    # JQuery
    echo'<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';
    echo'<script src="js/flot/jquery.flot.min.js"></script>';
    echo'<script src="js/jquery-ui.min.js"></script>';
    
?>
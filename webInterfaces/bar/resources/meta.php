<?php

    # Singleton
    require_once('resources/singleton.php');
    $singleton = Singleton::init();

    # Shortcut for some commonly used classes
    $controller = $singleton::$controller;
    $session = $singleton::$session;

    echo'<title>EASJ Beer Status</title>';
    echo'<meta charset="utf-8" />';
    echo'<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
    echo'<meta name="author" content="John Doe" />';
    echo'<meta name="robots" content="noindex, nofollow" />';
    echo'<meta name="viewport" content="width=device-width, initial-scale=0.8" />';

    # JQuery
    echo'<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';

    # Stylesheets
    echo'<!-- Using Skeleton Boilerplate -->';
    echo'<!-- Using Font Awesome         -->';
    echo'<link rel="stylesheet" href="css/font-awesome.min.css">';
    echo'<link href="css/normalize.css" rel="stylesheet">';
    echo'<link href="css/skeleton.css" rel="stylesheet">';
    echo'<link href="css/styles.css" rel="stylesheet">';
?>
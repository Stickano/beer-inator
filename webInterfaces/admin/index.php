<?php

ob_start();
echo'<!DOCTYPE html>';
echo'<html lang="da">';
echo'<head>';

	# Include the meta/headers
	require_once('resources/meta.php');

    if (isset($_POST['logout']))
        $singleton->logout();
    
echo'</head>';
echo'<body>';

    # Show messages, if any
    if ($singleton::$message){
        echo '<div class="errorContainer col-12" style="background-color:#'.$singleton::$messageColor.';">';
            echo '<b>'.$singleton::$message.'</b>';
            echo '<button class="closeErr right"><i class="fa fa-times" aria-hidden="true"></i></button>';
        echo '</div>';
    }

    if ($session->isset('loggedId')){
        echo'<div style="position:fixed; z-index:999; background-color:#254c6a; left:0; top:0; bottom:0; width:100px;">';
            $borderTop = "none";
            echo'<div class="leftBarButton" style="background-color:#8C9440; border:none; position:relative;"><img style="margin-bottom:-5px;" src="media/beerinatorIcon.png"></div>';
            if ($session->get('loggedRole') == 2){
                $borderTop = "1px solid rgba(204,204,204, .4)";
                echo'<a href="?cms" class="leftbarButton" style="border-top:none;" title="Skift Instillinger"><i class="fa fa-cogs" aria-hidden="true"></i></a>';
            }
            echo'<a href="?buyer" class="leftbarButton" style="border-top:'.$borderTop.';" title="Indkøbschef"><i class="fa fa-shopping-cart" aria-hidden="true"></i></i></a>';
            echo'<a href="?statistics" class="leftbarButton" title="Statistik"><i class="fa fa-line-chart" aria-hidden="true"></i></i></a>';
            echo'<a href="?password" class="leftbarButton" title="Skift Adgangskode"><i class="fa fa-user-circle" aria-hidden="true"></i></i></a>';
            echo '<form method="post" style="display:inline;">';
                echo'<button type="submit" class="leftbarButton" name="logout" title="Log ud"><i class="fa fa-sign-out" aria-hidden="true"></i></i></button>';
            echo'</form>';
        echo'</div>';
    }

    # This will load the appropriate view
    echo'<div style="position:absolute; width:100%; padding: 80px 15px 40px 115px; text-align:center;">';
        require_once('views/'.$singleton::$page.'.php');
    echo'</div>';
    
    echo'<script src="js/dynamics.js"></script>';

echo'</body>';
echo'</html>';
ob_end_flush();
?>
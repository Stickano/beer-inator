<?php
echo'<!DOCTYPE html>';
echo'<html lang="da">';
echo'<head>';

	# Include the meta/headers
	require_once('resources/meta.php');
    
echo'</head>';
echo'<body>';

    # Show errors, if any
    if ($singleton::$error){
        echo '<div class="errorContainer u-full-width">';
            echo '<b>'.$singleton::$error.'</b>';
            echo '<button class="closeErr u-pull-right"><i class="fa fa-times" aria-hidden="true"></i></button>';
        echo '</div>';
    }

    # This will load the appropriate view
    require_once('views/'.$singleton::$page.'.php');

    # Front end JS
    echo'<script src="js/dynamics.js"></script>';

echo'</body>';
echo'</html>';
?>

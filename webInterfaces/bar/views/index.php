<?php

if (isset($_POST['updateValues']))
    $controller->updateFridge();

echo'<div class="container" id="topContainer">';

    echo'<div class="row">';
        echo'<div class="u-full-width">';
            
            echo'<div class="percentageRuler" style="height:'.($controller->getRemainingHeight()-270).'px;">';
                echo'<div class="rulerContent">';
                    echo $controller->getPercentage().'%';
                echo'</div>';
            echo'</div>';
            
            if ($controller->getPercentage() == 100)
                echo'<div class="foam"></div>';

            echo'<div class="beerEmpty"></div>';
            echo'<div class="u-full-width">';
                echo'<div class="beerFull" style="height:'.$controller->getConvertedPercentage().'px; margin-top:'.$controller->getRemainingHeight().'px;"></div>';
            echo'</div>';
        echo'</div>';
    echo'</div>';

    # Human readable values
    echo'<a id="Change"></a>';
    echo'<div class="row">';
        echo'<div class="informationContainer">';

            echo'<a href="?change" class="changeAnchor">'.$controller->getValues(1).' / '.$controller->getValues().'</a>';

        echo'</div>';   # informationContainer close
    echo'</div>';       # Row close
echo'</div>';           # Container close

?>

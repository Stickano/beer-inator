<?php

if (isset($_POST['updateValues']))
    $controller->updateFridge();

        
echo'<div class="container" id="topContainer">';

    echo'<div class="row">';
        echo'<div class="u-full-width">';
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

            # Form if we are editing values
            if (isset($_GET['values'])) 
                echo'<form method="post">';

            echo'<table class="u-full-width">';
                echo'<tbody>';

                    # Max amount (beers)
                    echo'<tr>';
                        echo'<td>Maks Antal</td>';
                        echo'<td style="text-align:right;">';
                            echo $controller->getValues();
                        echo'</td>';
                    echo'</tr>';

                    # Remaining amount (beers)
                    echo'<tr>';
                        echo'<td>Resterende Antal</td>';
                        echo'<td style="text-align:right;">';
                            if (!isset($_GET['values']))
                                echo $controller->getValues(1);
                            else
                                echo'<input type="text" name="current" class="formInput" value="'.$controller->getValues(1).'">';
                        echo'</td>';
                    echo'</tr>';

                    # Buttons (Change/Update/Cancel)
                    echo'<tr>';
                        echo'<td> </td>';
                        echo'<td style="text-align:right;">';
                            if (!isset($_GET['values'])){
                                echo'<div class="u-pull-right"><a href="?values#Change" class="button button-primary" title="Manuelt Arbejde..suk">Skift Værdier</a></div>';
                            } else {
                                echo'<a href="index.php" class="button" style="margin-right:15px;">Annuller</a>';
                                echo'<input type="submit" class="button-primary" value="Opdater" name="updateValues">';
                            }
                        echo'</td>';
                    echo'</tr>';

                echo'</tbody>';
            echo'</table>';

            # End form if we are editing values
            if (isset($_GET['values'])) 
                echo'</form>';

        echo'</div>';   # informationContainer close
    echo'</div>';       # Row close
echo'</div>';           # Container close

?>

<?php
    if (isset($_POST['okUpdateTotal']))
        $controller->updateTotal();

?>

<!-- Update Total Value -->
<div style="max-width:520px; text-align: left; margin:0 auto;"> <h1>Lager</h1> </div>
<table style="margin: 10px auto; width:500px; ">
    <?php
    if ($controller->getTotal() <= $controller->getNotifyMin()){
        echo'<tr>';
            echo'<td colspan="2" style="padding:10px; color:red; text-align:left;">Der skal handles!</td>';
        echo'</tr>';
    }
    ?>
    <tr>
        <td style="text-align:left; border-bottom:1px solid darkgrey; vertical-align: top;">Antal øl på lageret</td>
        <td style="text-align:right; border-bottom:1px solid darkgrey; vertical-align: top;">
            
            <div id="totalVal"> <button id="totalValBut" class="settingValue"> <?php echo $controller->getTotal(); ?> </button> </div>
            
            <div id="totalChangeVal" style="margin:0; padding:0; height:1px; visibility:hidden;">
                <form method="post">
                    <input type="text" class="settingInput" name="value" value="<?php echo $controller->getTotal(); ?>" required>
                    <button class="settingButton" type="submit" name="okUpdateTotal"><i class="fa fa-check" aria-hidden="true"></i></button>
                    <button class="settingButton" id="totalValChangeCancel"><i class="fa fa-times" aria-hidden="true"></i></a>
                </form>
            </div>

        </td>
    </tr>
</table>


<!-- Web Scraper in action -->
<div style="max-width: 520px; text-align: left; margin:0 auto;"> <h1 style="margin-top:150px;">Tilbud</h1> </div>
<table style="margin:30px auto; padding:50px;" align="center">
<?php
# TODO: Twig?
foreach ($controller->webScrape() as $key) {
    echo'<tr>';
        echo'<td class="scrapeTd" style="background-image: url('.$key['image'].');"></td>';
        echo'<td style="text-align:left; margin-top:100px; padding:8px; vertical-align:top;"> <h3>'.$key['product'].'</h3> <br> <b><small>'.$key['store'].'</small></b></td>';
        echo'<td style="text-align:right; padding:8px; vertical-align:top;"> <h3>'.$key['price'].'</h3> <br> <small>'.$key['volumePrice'].' Per Liter</small></td>';
    echo'</tr>';
    echo'<tr><td colspan="3" style="height:20px;"></td></tr>';
}
?>
</table>


<script>
$(document).ready(function(){
    $('#totalValBut').click(function() {
        $('#totalVal').css('visibility', 'hidden').css('display', 'none');
        $('#totalChangeVal').css('visibility', 'visible');
        $('.settingInput').focus();
        return false;
    });

    $('#totalValChangeCancel').click(function() {
        $('#totalVal').css('visibility', 'visible').css('display', 'inline-block');
        $('#totalChangeVal').css('visibility', 'hidden');
        return false;
    });

});
</script>
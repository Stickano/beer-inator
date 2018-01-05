<?php

if (isset($_POST['okMinFridge']))
    $controller->updateMinFridge();

if (isset($_POST['okMaxFridge']))
    $controller->updateMaxFridge();

if (isset($_POST['okMinNotify']))
    $controller->updateMinNotify();

if (isset($_POST['okCreateProfile']))
    $controller->createProfile();

if (isset($_POST['okDeleteProfile']))
    $controller->deleteProfile();

if (isset($_POST['okResetPassword']))
    $controller->resetPassword();

?>

<!-- Minimum/Maximum Fridge/Storage Value -->
<div style="max-width:520px; text-align: left; margin:0 auto;"> <h1>Indstillinger</h1> </div>
<table style="margin: 10px auto; width:500px; ">
<tr>
    <td colspan="2" style="text-align:left;"> <h3>Notifikationer</h3> </td>
</tr>
<tr>
    <td colspan="2" style="height: 3px;"></td>
</tr>
<!-- Fridge Min -->
<tr>
    <td style="text-align:left; border-bottom:1px solid darkgrey; vertical-align: top;">Notificer Baren (Køleskabet)</td>
    <td style="text-align:right; border-bottom:1px solid darkgrey; vertical-align: top;">
        
        <div id="fridgeMin"> <button id="fridgeMinBut" class="settingValue"> <?php echo $controller->getFridgeMin(); ?> </button> </div>
        
        <div id="fridgeChangeMin" style="margin:0; padding:0; height:1px; visibility:hidden;">
            <form method="post">
                <input type="text" class="settingInput" name="value" value="<?php echo $controller->getFridgeMin(); ?>" required>
                <button class="settingButton" type="submit" name="okMinFridge"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button class="settingButton" id="fridgeMinChangeCancel"><i class="fa fa-times" aria-hidden="true"></i></a></button>
            </form>
        </div>
    </td>
</tr>
<!-- Notify Min (buyer) -->
<tr>
    <td style="text-align:left; border-bottom:1px solid darkgrey; vertical-align: top;">Notificer Indkøbschef (lager)</td>
    <td style="text-align:right; border-bottom:1px solid darkgrey; vertical-align: top;">
        
        <div id="notifyMin"> <button id="notifyMinBut" class="settingValue"> <?php echo $controller->getNotifyMin(); ?> </button> </div>
        
        <div id="notifyChangeMin" style="margin:0; padding:0; height:1px; visibility:hidden;">
            <form method="post">
                <input type="text" class="settingInput" name="value" value="<?php echo $controller->getNotifyMin(); ?>" required>
                <button class="settingButton" type="submit" name="okMinNotify"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button class="settingButton" id="notifyMinChangeCancel"><i class="fa fa-times" aria-hidden="true"></i></a></button>
            </form>
        </div>

    </td>
</tr>

<!-- Max Fridge -->
</tr>
<tr>
    <td colspan="2" style="height: 30px;"></td>
</tr>
<tr>
    <td colspan="2" style="text-align:left;"> <h3>Køleskabet</h3> </td>
</tr>
<tr>
    <td colspan="2" style="height: 3px;"></td>
</tr>
<tr>
    <td style="text-align:left; border-bottom:1px solid darkgrey; vertical-align: top;">Top kapacitet</td>
    <td style="text-align:right; border-bottom:1px solid darkgrey; vertical-align: top;">
        
        <div id="fridgeMax"> <button id="fridgeMaxBut" class="settingValue"> <?php echo $controller->getFridgeMax(); ?> </button> </div>
        
        <div id="fridgeChangeMax" style="margin:0; padding:0; height:1px; visibility:hidden;">
            <form method="post">
                <input type="text" class="settingInput" name="value" value="<?php echo $controller->getFridgeMax(); ?>" required>
                <button class="settingButton" type="submit" name="okMaxFridge"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button class="settingButton" id="okMaxFridgeChangeCancel"><i class="fa fa-times" aria-hidden="true"></i></a></button>
            </form>
        </div>
    </td>
</table>


<!-- Create Profile -->
<div style="margin:150px auto 0 auto; text-align:left; width:520px;"><h1>Profiler</h1></div>
<div style="width:500px; margin:10px auto; text-align: left;">
    <button id="addProfileButton" title="Tilføj Profil"> <i class="fa fa-user-plus" aria-hidden="true"></i> </button>
</div>

<div id="createProfile" style="visibility: hidden; display:none;">
    <button id="addProfileCancel" class="right"> <i class="fa fa-times" aria-hidden="true"></i> </button>
    <form method="post">
    <table style="margin: 10px auto; width:500px; ">
        <tr>
            <td style="text-align:left; vertical-align: top;">
                <input style="border-left:1px solid darkgrey; border-top:1px solid darkgrey; border-right:1px solid darkgrey;" type="text" id="uname" name="uname" class="createProfileInput" placeholder="E-mail" required>
            </td>
            <td style="text-align:right; vertical-align: top;">
                <input style="border-top:1px solid darkgrey; border-right:1px solid darkgrey;" type="text" name="fullname" class="createProfileInput" placeholder="Fulde Navn" required>
            </td>
        </tr>
        <tr>
            <td style="text-align:left; vertical-align: top;">
                <select style="border:1px solid darkgrey; padding:11px; text-indent: 30%;" name="role" class="createProfileInput">
                    <option value="1" class="createProfileInput">Indkøbschef</option>
                    <option value="2" class="createProfileInput">Administrator</option>
                </select>
            </td>
            <td style="text-align:right; vertical-align: top;">
                <input style="border-bottom:1px solid darkgrey; border-right:1px solid darkgrey; border-top:1px solid darkgrey; color:white; background-color:#6991ac;" type="submit" name="okCreateProfile" class="createProfileInput" value="Opret">
            </td>
        </tr>
    </table>
    </form>
</div>

<table style="margin:0 auto; text-align: left;">
<?php
# TODO: Twig?
foreach ($controller->getAllProfiles() as $key) {
    # Skip if it's the logged-in user
    if ($key['id'] == $session->get('loggedId'))
        continue;

    # Convert role
    $role = "Indkøbschef";
    if ($key['role'] == 2)
        $role = "Administrator";

    echo'<tr>';
        echo'<td style="padding:15px;">';
            echo'<form method="post" style="display:inline;">';
                echo'<input type="hidden" name="id" value="'.$key['id'].'">';
                echo'<button class="settingButton" type="submit" title="Slet Profil" name="okDeleteProfile"><i class="fa fa-times" aria-hidden="true"></i></button>';
                echo'<button class="settingButton" style="margin-left:10px;" title="Nulstil Adgangskode" type="submit" name="okResetPassword"><i class="fa fa-undo" aria-hidden="true"></i></button>';
            echo'</form>';
        echo'</td>';

        echo'<td style="padding:15px;">'.$key['fullname'].'</td>';
        echo'<td style="padding:15px;">'.$key['uname'].'</td>';
        echo'<td style="padding:15px;">'.$role.'</td>';
    echo'</tr>';
}
?>
</table>


<script>
$(document).ready(function(){
    $('#fridgeMinBut').click(function() {
        $('#fridgeMin').css('visibility', 'hidden').css('display', 'none');
        $('#fridgeChangeMin').css('visibility', 'visible');
        $('.settingInput').focus();
        return false;
    });
    $('#fridgeMinChangeCancel').click(function() {
        $('#fridgeMin').css('visibility', 'visible').css('display', 'inline-block');
        $('#fridgeChangeMin').css('visibility', 'hidden');
        return false;
    });

    $('#notifyMinBut').click(function() {
        $('#notifyMin').css('visibility', 'hidden').css('display', 'none');
        $('#notifyChangeMin').css('visibility', 'visible');
        $('.settingInput').focus();
        return false;
    });
    $('#notifyMinChangeCancel').click(function() {
        $('#notifyMin').css('visibility', 'visible').css('display', 'inline-block');
        $('#notifyChangeMin').css('visibility', 'hidden');
        return false;
    });

    $('#fridgeMaxBut').click(function() {
        $('#fridgeMax').css('visibility', 'hidden').css('display', 'none');
        $('#fridgeChangeMax').css('visibility', 'visible');
        $('.settingInput').focus();
        return false;
    });
    $('#okMaxFridgeChangeCancel').click(function() {
        $('#fridgeMax').css('visibility', 'visible').css('display', 'inline-block');
        $('#fridgeChangeMax').css('visibility', 'hidden');
        return false;
    });

    $('#addProfileButton').click(function() {
        $('#createProfile').css('visibility', 'visible').css('display', 'inline-block');
        $('#addProfileButton').css('visibility', 'hidden').css('display', 'none');
        $('#uname').focus();
        return false; 
    });
    $('#addProfileCancel').click(function() {
        $('#createProfile').css('visibility', 'hidden').css('display', 'none');
        $('#addProfileButton').css('visibility', 'visible').css('display', 'inline-block');
        return false; 
    });

});
</script>

<?php
    if (isset($_POST['okChangePassword']))
        $controller->changePassword();
?>


<div style="width:520px; text-align:left; margin:20px auto 0 auto;"><h3>Skift Adgangskode</h3></div>
<form method="post">
<table style="margin: 10px auto; width:500px; ">
    <tr>
        <td>
            <input type="password" name="current" class="pwInput" placeholder="Nuværende Adgangskode" autofocus>
        </td>
    </tr>
    <tr>
        <td>
            <input type="password" name="new1" class="pwInput" placeholder="Ny Adgangskode">
        </td>
    </tr>
    <tr>
        <td>
            <input type="password" name="new2" class="pwInput" placeholder="Gentag" style="border-bottom: 1px solid darkgrey;">
        </td>
    </tr>
    <tr>
        <td>
            <input type="submit" name="okChangePassword" value="Opdater" class="createProfileInput right" style="background-color: #6991ac; margin-top:5px; color:white; width:50%;">
        </td>
    </tr>
</table>
</form>

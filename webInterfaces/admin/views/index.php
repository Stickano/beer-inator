<?php
if (isset($_POST['okLogin']))
        $controller->login();
?>


<form method="post">
<table style="margin: 10px auto; width:500px; ">
    <tr>
        <td style="width:150px;">
            <img src="media/beerinatorIcon.png">
        </td>
        <td style="vertical-align: bottom; padding-bottom: 10px; text-align: left; padding-left:25px;">
            <h1>Beer Inator</h1>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="text" name="uname" class="pwInput" placeholder="E-mail" autofocus>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="password" name="upass" class="pwInput" placeholder="Adgangskode">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="okLogin" value="Login" class="createProfileInput right" style="background-color: #6991ac; margin-top:5px; color:white; width:50%;">
        </td>
    </tr>
</table>
</form>


<?php
    foreach ($controller->getAllValues() as $key => $value) {
        echo 'Numero: '.$key.'<br>';
        foreach ($value as $sub) {
            echo $sub.'<br>';
        }
    }

    echo $controller->getToDate() - $controller->getFromDate();

?>


<!-- Statistics -->
<div style="width:520px; text-align: left; margin:0 auto;"><h1>Statistik</h1></div>

<form method="get" action="" style="width:500px; margin:0 auto;">
    <input type="hidden" name="statistics">
    <input type="text" class="datepick" name="from" id="from" placeholder="Fra Dato">
    <input type="text" class="datepick" name="to" id="to" placeholder="Til Dato">
    <input type="submit" name="okPeriod" value="Søg" class="searchButton">
</form>

<div class="graph-container">
    <div id="placeholder" class="graph-placeholder" style="padding: 0px; position: relative;">
        <canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 818px; height: 413px;" width="818" height="413"></canvas>
        <div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);">
            <div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 102px; top: 395px; left: 30px; text-align: center;">0</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 102px; top: 395px; left: 145px; text-align: center;">2</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 102px; top: 395px; left: 260px; text-align: center;">4</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 102px; top: 395px; left: 375px; text-align: center;">6</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 102px; top: 395px; left: 490px; text-align: center;">8</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 102px; top: 395px; left: 601px; text-align: center;">10</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 102px; top: 395px; left: 716px; text-align: center;">12</div>
            </div>
            <div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 382px; left: 5px; text-align: right;">-2.5</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 327px; left: 9px; text-align: right;">0.0</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 273px; left: 9px; text-align: right;">2.5</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 218px; left: 9px; text-align: right;">5.0</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 164px; left: 9px; text-align: right;">7.5</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 109px; left: 2px; text-align: right;">10.0</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 55px; left: 2px; text-align: right;">12.5</div>
                <div class="flot-tick-label tickLabel" style="position: absolute; top: 0px; left: 2px; text-align: right;">15.0</div>
            </div>
        </div>
        <canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 818px; height: 413px;" width="818" height="413"></canvas>
    </div>
</div>


<script>
$(document).ready(function(){

    //var data = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];

    <?php
        $values = "";
        foreach ($controller->getViewValues() as $key) {
            $values .= "[".$controller->formatConverter($key['dateTime']).", ".$key['amount']."],";
        }
        echo "var data = [".$values."];";
    ?>


    $.plot("#placeholder", [data]);

    $(".datepick").datepicker({
        monthNames: [ "Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December" ],
        dateFormat: "yymmdd",
        firstDay: 1,
        dayNames: [ "Søndag", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag" ],
        dayNamesMin: [ "Sø", "Ma", "Ti", "On", "To", "Fr", "Lø" ]
    });

});
</script>